<?php

require_once __DIR__ . '/Model.php';

/**
 * Turno — Model da tabela `turnos`.
 *
 * Colunas: id (int, NÃO auto_increment), turno (texto).
 * Relação: alunos.turno_id -> turnos.id (um turno tem vários alunos).
 */
class Turno extends ADODB_Model {
    protected static $table  = 'turnos';
    protected static $pk     = 'id';

    /**
     * Próximo id disponível (a coluna id não é auto_increment).
     */
    public static function proximoId() {
        $db = self::$db;
        $rs = $db->Execute("SELECT COALESCE(MAX(id),0) AS m FROM turnos");
        return ($rs !== false) ? ((int)$rs->fields['m'] + 1) : 1;
    }

    /**
     * Já existe outro turno com o mesmo nome (case-insensitive)?
     */
    public static function duplicado($turno, $ignorarId = null) {
        $db = self::$db;
        $sql = "SELECT id FROM turnos WHERE LOWER(turno) = LOWER(?)";
        $params = array($turno);
        if ($ignorarId !== null) {
            $sql .= " AND id <> ?";
            $params[] = (int)$ignorarId;
        }
        $sql .= " LIMIT 1";
        $rs = $db->Execute($sql, $params);
        return ($rs !== false && $rs->RecordCount() > 0) ? (int)$rs->fields['id'] : null;
    }

    /**
     * Quantidade de alunos vinculados a este turno (alunos.turno_id).
     */
    public function countAlunos() {
        $db = self::$db;
        return (int)$db->GetOne(
            "SELECT COUNT(*) FROM alunos WHERE turno_id = ?",
            array($this->getKey())
        );
    }

    /**
     * Alunos vinculados a este turno.
     */
    public function alunos() {
        $db = self::$db;
        $rs = $db->Execute(
            "SELECT id, registro, nome, ingresso FROM alunos WHERE turno_id = ? ORDER BY nome",
            array($this->getKey())
        );
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array(
                    'id'       => (int)$rs->fields['id'],
                    'registro' => (int)$rs->fields['registro'],
                    'nome'     => $rs->fields['nome'],
                    'ingresso' => $rs->fields['ingresso'],
                );
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Lista de turnos (id + nome) para seletores de formulário.
     */
    public static function seleciona() {
        $db = self::$db;
        $rs = $db->Execute("SELECT id, turno FROM turnos ORDER BY turno");
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array('id' => (int)$rs->fields['id'], 'turno' => $rs->fields['turno']);
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Lista de turnos com a quantidade de alunos (para o índice).
     */
    public static function listar() {
        $db = self::$db;
        $rs = $db->Execute(
            "SELECT t.id, t.turno,
                    (SELECT COUNT(*) FROM alunos a WHERE a.turno_id = t.id) AS num_alunos
             FROM turnos AS t
             ORDER BY t.turno"
        );
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array(
                    'id'         => (int)$rs->fields['id'],
                    'turno'      => $rs->fields['turno'],
                    'num_alunos' => (int)$rs->fields['num_alunos'],
                );
                $rs->MoveNext();
            }
        }
        return $out;
    }
}

?>
