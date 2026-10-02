<?php
/**
 * Aluno.php — Modelo da tabela `alunos` (cadastro de alunos do curso).
 *
 * Nota: a coluna `id` NÃO é auto_increment. Ao inserir um novo aluno é
 * necessário gerar o id manualmente (via proximoId()).
 */
if (!class_exists('Aluno', false)) {

class Aluno extends ADODB_Model {
    protected static $table  = 'alunos';
    protected static $pk     = 'id';
    protected static $hidden = array();

    /**
     * Próximo id disponível (a coluna id não é auto_increment).
     */
    public static function proximoId() {
        $db = static::db();
        $rs = $db->Execute("SELECT COALESCE(MAX(id),0) AS m FROM alunos");
        return ($rs !== false) ? ((int)$rs->fields['m'] + 1) : 1;
    }

    /**
     * Já existe outro aluno com o mesmo registro (matrícula)?
     */
    public static function registroDuplicado($registro, $ignorarId = null) {
        $db = static::db();
        $sql = "SELECT id FROM alunos WHERE registro = ?";
        $params = array((int)$registro);
        if ($ignorarId !== null) {
            $sql .= " AND id <> ?";
            $params[] = (int)$ignorarId;
        }
        $sql .= " LIMIT 1";
        $rs = $db->Execute($sql, $params);
        return ($rs !== false && $rs->RecordCount() > 0) ? (int)$rs->fields['id'] : null;
    }

    /**
     * Busca um aluno pelo id (com contagem de estágios e inscrições).
     */
    public static function buscar($id) {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT a.*, "
            . "(SELECT COUNT(*) FROM estagiarios e WHERE e.aluno_id = a.id) AS num_estagios, "
            . "(SELECT COUNT(*) FROM inscricoes i WHERE i.registro = a.registro) AS num_inscricoes "
            . "FROM alunos a WHERE a.id = ? LIMIT 1",
            array((int)$id)
        );
        if ($rs === false || $rs->RecordCount() == 0) {
            return null;
        }
        return $rs->fields;
    }

    /**
     * Histórico de estágios do aluno, com instituição, supervisor e professor
     * resolvidos na própria consulta (sem N+1).
     */
    public static function historicoEstagios($aluno_id) {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT e.id AS estagiario_id, e.tc, e.nivel, e.periodo, e.nota, e.ch, "
            . "e.instituicao_id, e.supervisor_id, e.professor_id, "
            . "i.instituicao AS instituicao_nome, "
            . "s.nome AS supervisor_nome, p.nome AS professor_nome "
            . "FROM estagiarios e "
            . "LEFT JOIN instituicoes i ON i.id = e.instituicao_id "
            . "LEFT JOIN supervisores s ON s.id = e.supervisor_id "
            . "LEFT JOIN professores p ON p.id = e.professor_id "
            . "WHERE e.aluno_id = ? ORDER BY e.periodo",
            array((int)$aluno_id)
        );
        if ($rs === false) {
            return array();
        }
        $out = array();
        while (!$rs->EOF) {
            $out[] = array(
                'estagiario_id'    => (int)$rs->fields['estagiario_id'],
                'tc'               => $rs->fields['tc'],
                'nivel'            => $rs->fields['nivel'],
                'periodo'          => $rs->fields['periodo'],
                'nota'             => $rs->fields['nota'],
                'ch'               => $rs->fields['ch'],
                'instituicao_id'   => (int)$rs->fields['instituicao_id'],
                'instituicao'      => ($rs->fields['instituicao_nome'] !== null) ? $rs->fields['instituicao_nome'] : 'Sem dados',
                'supervisor_id'    => (int)$rs->fields['supervisor_id'],
                'supervisor'       => ($rs->fields['supervisor_nome'] !== null) ? $rs->fields['supervisor_nome'] : 'Sem dados',
                'professor_id'     => (int)$rs->fields['professor_id'],
                'professor'        => ($rs->fields['professor_nome'] !== null) ? $rs->fields['professor_nome'] : 'Sem dados',
            );
            $rs->MoveNext();
        }
        return $out;
    }

    /**
     * Lista periódica de alunos para o índice (com nº de estágios).
     *
     * @param string $busca Valor a pesquisar no nome/registro ('' = todos).
     * @param string $periodo Filtra alunos que estagiaram no período ('' = todos).
     * @param int    $instituicao_id Filtra por instituição de estágio (0 = todas).
     * @param string $orderby  Coluna de ordenação (whitelist aplicada no chamador).
     */
    public static function listar($busca = '', $periodo = '', $instituicao_id = 0, $orderby = 'a.nome') {
        $db = static::db();

        $sql = "SELECT a.id, a.registro, a.nome, a.ingresso, a.turno, a.email, a.telefone, a.celular, "
             . "a.codigo_telefone, a.codigo_celular, a.user_id, "
             . "GROUP_CONCAT(DISTINCT e.periodo ORDER BY e.periodo SEPARATOR ', ') AS periodos_estagio, "
             . "(SELECT COUNT(*) FROM estagiarios e2 WHERE e2.aluno_id = a.id) AS num_estagios, "
             . "MIN(e.instituicao_id) AS instituicao_id, "
             . "MIN(i.instituicao) AS instituicao "
             . "FROM alunos a "
             . "LEFT JOIN estagiarios e ON e.aluno_id = a.id "
             . "LEFT JOIN instituicoes i ON i.id = e.instituicao_id";

        $where = array();
        $params = array();
        if ($busca !== '') {
            $where[] = "(a.nome LIKE ? OR CAST(a.registro AS CHAR) LIKE ?)";
            $params[] = "%$busca%";
            $params[] = "%$busca%";
        }
        if ($periodo !== '') {
            $where[] = "e.periodo = ?";
            $params[] = $periodo;
        }
        if ($instituicao_id > 0) {
            $where[] = "e.instituicao_id = ?";
            $params[] = (int)$instituicao_id;
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " GROUP BY a.id ORDER BY " . $orderby;

        $rs = $db->Execute($sql, $params);
        if ($rs === false) {
            die("Não foi possível consultar a tabela alunos");
        }
        $out = array();
        while (!$rs->EOF) {
            $out[] = array(
                'id'              => (int)$rs->fields['id'],
                'registro'        => (int)$rs->fields['registro'],
                'nome'            => $rs->fields['nome'],
                'ingresso'        => $rs->fields['ingresso'],
                'turno'           => $rs->fields['turno'],
                'email'           => strtolower((string)$rs->fields['email']),
                'telefone'        => self::formataTelefone($rs->fields['codigo_telefone'], $rs->fields['telefone']),
                'celular'         => self::formataTelefone($rs->fields['codigo_celular'], $rs->fields['celular']),
                'num_estagios'    => (int)$rs->fields['num_estagios'],
                'periodos_estagio'=> $rs->fields['periodos_estagio'],
                'instituicao'     => $rs->fields['instituicao'],
                'instituicao_id'  => (int)$rs->fields['instituicao_id'],
            );
            $rs->MoveNext();
        }
        return $out;
    }

    /**
     * Períodos distintos com estágios (para o filtro do índice).
     */
    public static function periodos() {
        $db = static::db();
        $rs = $db->Execute("SELECT DISTINCT periodo FROM estagiarios ORDER BY periodo");
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = $rs->fields['periodo'];
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Instituições que têm estágios (para o filtro do índice).
     */
    public static function listarInstituicoes() {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT DISTINCT i.id, i.instituicao FROM estagiarios e "
            . "JOIN instituicoes i ON i.id = e.instituicao_id "
            . "WHERE e.instituicao_id IS NOT NULL ORDER BY i.instituicao"
        );
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array('id' => (int)$rs->fields['id'], 'instituicao' => $rs->fields['instituicao']);
                $rs->MoveNext();
            }
        }
        return $out;
    }

    private static function formataTelefone($ddd, $numero) {
        if (empty($numero)) return '';
        $ddd = (int)$ddd ?: 21;
        return "($ddd) $numero";
    }
}

}

?>