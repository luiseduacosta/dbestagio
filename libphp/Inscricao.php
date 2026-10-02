<?php
/**
 * Inscricao.php — Modelo da tabela `inscricoes` (alunos inscritos nas ofertas do mural).
 *
 * O módulo lista/insere/edita/exclui o registro de um aluno em uma oferta de
 * estágio (mural_estagios). A listagem faz JOIN com `alunos` e `mural_estagios`
 * na própria consulta (sem N+1).
 */
if (!class_exists('Inscricao', false)) {

class Inscricao extends ADODB_Model {
    protected static $table  = 'inscricoes';
    protected static $pk     = 'id';
    protected static $hidden = array();

    /**
     * Períodos distintos com inscrições registradas (do mais recente ao mais antigo).
     */
    public static function periodos() {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT DISTINCT periodo FROM inscricoes WHERE periodo IS NOT NULL AND periodo <> '' ORDER BY periodo DESC"
        );
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
     * Ofertas do mural de um período, para popular o seletor do formulário.
     *
     * @param string $periodo Vazio = todas as ofertas.
     * @return array[]['id'|'instituicao']
     */
    public static function ofertasPorPeriodo($periodo = '') {
        $db = static::db();
        $params = array();
        $sql = "SELECT id, instituicao, periodo FROM mural_estagios";
        if ($periodo !== '') {
            $sql .= " WHERE periodo = ?";
            $params[] = $periodo;
        }
        $sql .= " ORDER BY instituicao";
        $rs = $db->Execute($sql, $params);
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array(
                    'id'          => (int)$rs->fields['id'],
                    'instituicao' => $rs->fields['instituicao'],
                    'periodo'     => $rs->fields['periodo'],
                );
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Busca um aluno na tabela `alunos` pelo registro (matrícula).
     *
     * @param int $registro
     * @return array|null
     */
    public static function alunoPorRegistro($registro) {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT id, registro, nome, email FROM alunos WHERE registro = ? LIMIT 1",
            array((int)$registro)
        );
        if ($rs === false || $rs->RecordCount() == 0) {
            return null;
        }
        return $rs->fields;
    }

    /**
     * Já existe uma inscrição do mesmo aluno (registro) na mesma oferta/período?
     */
    public static function duplicada($registro, $muralestagio_id, $periodo, $ignorarId = null) {
        $db = static::db();
        $sql = "SELECT id FROM inscricoes WHERE registro = ? AND muralestagio_id = ? AND periodo = ?";
        $params = array((int)$registro, (int)$muralestagio_id, $periodo);
        if ($ignorarId !== null) {
            $sql .= " AND id <> ?";
            $params[] = (int)$ignorarId;
        }
        $sql .= " LIMIT 1";
        $rs = $db->Execute($sql, $params);
        return ($rs !== false && $rs->RecordCount() > 0) ? (int)$rs->fields['id'] : null;
    }

    /**
     * Lista inscrições (com aluno e oferta), opcionalmente filtradas por oferta.
     *
     * @param string $periodo         Período ('' = todos).
     * @param int    $muralestagio_id Filtra uma oferta específica (0 = todas).
     * @return array Registros formatados (data em dd-mm-aaaa e timestamp legível).
     */
    public static function listar($periodo = '', $muralestagio_id = 0) {
        $db = static::db();

        $sql = "SELECT i.id, i.registro, i.muralestagio_id, i.data, i.periodo, i.timestamp, "
             . "i.aluno_id, m.instituicao AS oferta_instituicao, "
             . "al.nome AS aluno_nome, al.email AS aluno_email, "
             . "al.telefone AS aluno_telefone, al.celular AS aluno_celular "
             . "FROM inscricoes AS i "
             . "LEFT JOIN mural_estagios AS m ON m.id = i.muralestagio_id "
             . "LEFT JOIN alunos AS al        ON al.registro = i.registro";

        $where = array();
        $params = array();
        if ($periodo !== '') {
            $where[] = "i.periodo = ?";
            $params[] = $periodo;
        }
        if ($muralestagio_id > 0) {
            $where[] = "i.muralestagio_id = ?";
            $params[] = (int)$muralestagio_id;
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY i.data DESC, i.id DESC";

        $rs = $db->Execute($sql, $params);
        if ($rs === false) {
            die("Não foi possível consultar a tabela inscricoes");
        }

        $out = array();
        while (!$rs->EOF) {
            $out[] = array(
                'id'                 => (int)$rs->fields['id'],
                'registro'           => (int)$rs->fields['registro'],
                'muralestagio_id'    => (int)$rs->fields['muralestagio_id'],
                'data'               => self::dataTexto($rs->fields['data']),
                'data_iso'           => self::dataIso($rs->fields['data']),
                'periodo'            => $rs->fields['periodo'],
                'timestamp'          => $rs->fields['timestamp'],
                'aluno_id'           => (int)$rs->fields['aluno_id'],
                'oferta_instituicao' => $rs->fields['oferta_instituicao'],
                'aluno_nome'         => $rs->fields['aluno_nome'],
                'aluno_email'        => $rs->fields['aluno_email'],
                'aluno_telefone'     => $rs->fields['aluno_telefone'],
                'aluno_celular'      => $rs->fields['aluno_celular'],
            );
            $rs->MoveNext();
        }
        return $out;
    }

    /**
     * Retorna UMA inscrição com dados do aluno e da oferta, ou null.
     */
    public static function buscar($id) {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT i.id, i.registro, i.muralestagio_id, i.data, i.periodo, i.timestamp, "
            . "i.aluno_id, m.instituicao AS oferta_instituicao, "
            . "al.nome AS aluno_nome, al.email AS aluno_email, "
            . "al.telefone AS aluno_telefone, al.celular AS aluno_celular "
            . "FROM inscricoes AS i "
            . "LEFT JOIN mural_estagios AS m ON m.id = i.muralestagio_id "
            . "LEFT JOIN alunos AS al        ON al.registro = i.registro "
            . "WHERE i.id = ? LIMIT 1",
            array((int)$id)
        );
        if ($rs === false || $rs->RecordCount() == 0) {
            return null;
        }
        $f = $rs->fields;
        return array(
            'id'                 => (int)$f['id'],
            'registro'           => (int)$f['registro'],
            'muralestagio_id'    => (int)$f['muralestagio_id'],
            'data'               => self::dataTexto($f['data']),
            'data_iso'           => self::dataIso($f['data']),
            'periodo'            => $f['periodo'],
            'timestamp'          => $f['timestamp'],
            'aluno_id'           => (int)$f['aluno_id'],
            'oferta_instituicao' => $f['oferta_instituicao'],
            'aluno_nome'         => $f['aluno_nome'],
            'aluno_email'        => $f['aluno_email'],
            'aluno_telefone'     => $f['aluno_telefone'],
            'aluno_celular'      => $f['aluno_celular'],
        );
    }

    private static function dataIso($date) {
        if (empty($date) || $date === '0000-00-00') return '';
        $ts = strtotime($date);
        return ($ts === false) ? '' : date('Y-m-d', $ts);
    }

    private static function dataTexto($date) {
        $iso = self::dataIso($date);
        if ($iso === '') return '';
        $ts = strtotime($iso);
        return ($ts === false) ? '' : date('d-m-Y', $ts);
    }
}

}

?>