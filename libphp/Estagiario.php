<?php

if (!class_exists('Estagiario', false)) {

/**
 * Estagiario — Model da tabela `estagiarios`.
 *
 * Colunas: id (auto_increment), registro, aluno_id, nivel, tc, tc_solicitacao,
 *          instituicao_id, supervisor_id, professor_id, periodo, nota, ch,
 *          complemento_id, ajuste2020, benetransporte, benealimentacao,
 *          benebolsa, observacoes.
 */
class Estagiario extends ADODB_Model {
    protected static $table  = 'estagiarios';
    protected static $pk     = 'id';

    // Colunas graváveis via preencher()/save().
    private static $colunas = array(
        'registro', 'aluno_id', 'nivel', 'tc', 'tc_solicitacao',
        'instituicao_id', 'supervisor_id', 'professor_id', 'periodo',
        'nota', 'ch', 'complemento_id', 'ajuste2020',
        'benetransporte', 'benealimentacao', 'benebolsa', 'observacoes',
    );

    /**
     * Preenche as colunas graváveis a partir de um array associativo.
     */
    public function preencher(array $dados) {
        foreach (self::$colunas as $col) {
            if (array_key_exists($col, $dados)) {
                $this->$col = $dados[$col];
            }
        }
        return true;
    }

    /**
     * Verifica se já existe um registro de estágio do mesmo aluno no mesmo período
     * e nível. Retorna o id conflitante ou null. Aceita um id a ignorar (edição).
     */
    public static function duplicado($aluno_id, $periodo, $nivel, $ignorarId = null) {
        $db = static::db();
        $sql = "SELECT id FROM estagiarios WHERE aluno_id = ? AND periodo = ? AND nivel = ?";
        $param = array((int)$aluno_id, $periodo, $nivel);
        if ($ignorarId !== null) {
            $sql .= " AND id <> ?";
            $param[] = (int)$ignorarId;
        }
        $sql .= " LIMIT 1";
        $rs = $db->Execute($sql, $param);
        return ($rs !== false && $rs->RecordCount() > 0) ? (int)$rs->fields['id'] : null;
    }

    /**
     * Busca um estágio pelo id, com aluno, instituição, supervisor e professor
     * resolvidos na própria consulta (sem N+1).
     */
    public static function buscar($id) {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT e.*, "
            . "a.nome AS aluno_nome, a.registro AS aluno_registro, "
            . "i.instituicao AS instituicao, "
            . "s.nome AS supervisor, "
            . "p.nome AS professor "
            . "FROM estagiarios e "
            . "LEFT JOIN alunos a ON a.id = e.aluno_id "
            . "LEFT JOIN instituicoes i ON i.id = e.instituicao_id "
            . "LEFT JOIN supervisores s ON s.id = e.supervisor_id "
            . "LEFT JOIN professores p ON p.id = e.professor_id "
            . "WHERE e.id = ? LIMIT 1",
            array((int)$id)
        );
        if ($rs === false || $rs->RecordCount() == 0) {
            return null;
        }
        return $rs->fields;
    }

    /**
     * Lista de estágios para o índice (DataTables), com JOINs sem N+1.
     *
     * @param string $periodo Filtra por período (valida contra a lista fechada no chamador).
     * @param string $busca   Valor a pesquisar em aluno (nome/registro) ou instituição.
     * @param string $orderby Coluna de ordenação (whitelist aplicada no chamador).
     */
    public static function listar($periodo = '', $busca = '', $orderby = 'e.periodo DESC, a.nome') {
        $db = static::db();

        $sql = "SELECT e.id, e.registro, e.periodo, e.nivel, e.tc, e.nota, e.ch, "
             . "e.aluno_id, a.nome AS aluno_nome, "
             . "e.instituicao_id, i.instituicao, "
             . "e.supervisor_id, s.nome AS supervisor, "
             . "e.professor_id, p.nome AS professor "
             . "FROM estagiarios e "
             . "LEFT JOIN alunos a ON a.id = e.aluno_id "
             . "LEFT JOIN instituicoes i ON i.id = e.instituicao_id "
             . "LEFT JOIN supervisores s ON s.id = e.supervisor_id "
             . "LEFT JOIN professores p ON p.id = e.professor_id ";

        $where  = array();
        $params = array();
        if ($periodo !== '') {
            $where[]  = "e.periodo = ?";
            $params[] = $periodo;
        }
        if ($busca !== '') {
            $where[]  = "(a.nome LIKE ? OR CAST(e.registro AS CHAR) LIKE ? OR i.instituicao LIKE ?)";
            $params[] = "%$busca%";
            $params[] = "%$busca%";
            $params[] = "%$busca%";
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY " . $orderby;

        $rs = $db->Execute($sql, $params);
        if ($rs === false) {
            die("Não foi possível consultar a tabela estagiarios");
        }
        $out = array();
        while (!$rs->EOF) {
            $f = $rs->fields;
            $out[] = array(
                'id'              => (int)$f['id'],
                'registro'        => (int)$f['registro'],
                'aluno_id'        => (int)$f['aluno_id'],
                'aluno_nome'      => $f['aluno_nome'],
                'instituicao_id'  => (int)$f['instituicao_id'],
                'instituicao'     => ($f['instituicao'] !== null) ? $f['instituicao'] : 'Sem dados',
                'supervisor'      => ($f['supervisor'] !== null) ? $f['supervisor'] : 'Sem dados',
                'professor'       => ($f['professor'] !== null) ? $f['professor'] : 'Sem dados',
                'periodo'         => $f['periodo'],
                'nivel'           => $f['nivel'],
                'tc'              => (int)$f['tc'],
                'nota'            => ($f['nota'] !== null) ? $f['nota'] : '',
                'ch'              => ($f['ch'] !== null) ? (int)$f['ch'] : '',
            );
            $rs->MoveNext();
        }
        return $out;
    }

    /**
     * Lista dos períodos disponíveis (para o filtro/select).
     */
    public static function periodos() {
        $db = static::db();
        $rs = $db->Execute("SELECT DISTINCT periodo FROM estagiarios ORDER BY periodo DESC");
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
     * Lista de alunos (para o select do formulário).
     */
    public static function alunos() {
        $db = static::db();
        $rs = $db->Execute("SELECT id, registro, nome FROM alunos ORDER BY nome");
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array('id' => (int)$rs->fields['id'], 'registro' => (int)$rs->fields['registro'], 'nome' => $rs->fields['nome']);
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Lista de níveis possíveis para um estágio.
     */
    public static function niveis() {
        return array('', '1', '2', '3', '4', '9');
    }

}
}
