<?php

require_once __DIR__ . '/Model.php';

/**
 * Visita — Model da tabela `visitas`.
 *
 * Colunas: id (auto), instituicao_id, professor_id (opcional), data,
 *          motivo, responsavel, descricao (opcional), avaliacao.
 * Relações:
 *  - instituicao_id -> instituicoes.id
 *  - professor_id   -> professores.id
 */
class Visita extends ADODB_Model {
    protected static $table  = 'visitas';
    protected static $pk     = 'id';

    /**
     * Nome da instituição vinculada.
     */
    public function instituicaoNome() {
        if (empty($this->_data['instituicao_id'])) {
            return '';
        }
        return (string)self::$db->GetOne(
            "SELECT instituicao FROM instituicoes WHERE id = ?",
            array($this->_data['instituicao_id'])
        );
    }

    /**
     * Nome do professor vinculado.
     */
    public function professorNome() {
        if (empty($this->_data['professor_id'])) {
            return '';
        }
        return (string)self::$db->GetOne(
            "SELECT nome FROM professores WHERE id = ?",
            array($this->_data['professor_id'])
        );
    }

    /**
     * Lista com os dados das visitas (join com instituicao/professor) para o index.
     * Suporta filtro por instituição (opcional).
     */
    public static function listar($instituicao_id = 0) {
        $db = self::$db;
        $sql = "SELECT v.*, i.instituicao AS nome_instituicao, p.nome AS nome_professor
                FROM visitas AS v
                LEFT JOIN instituicoes AS i ON i.id = v.instituicao_id
                LEFT JOIN professores AS p ON p.id = v.professor_id";
        $params = array();
        if ($instituicao_id > 0) {
            $sql .= " WHERE v.instituicao_id = ?";
            $params[] = $instituicao_id;
        }
        $sql .= " ORDER BY v.data DESC, v.id DESC";

        $rs = $db->Execute($sql, $params);
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = $rs->fields;
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Lista de mãscaras válidas para motivos e avaliações.
     */
    public static function motivos() {
        return array('Abertura de campo', 'Renovação de convênio', 'Rotina', 'Encerramento de campo', 'Outro');
    }

    public static function avaliacoes() {
        return array('Boa', 'Ótima', 'Regular', 'Ruim');
    }

    /**
     * Lista de professores para o seletor do formulário.
     */
    public static function listar_professores() {
        $db = self::$db;
        $rs = $db->Execute("SELECT id, nome FROM professores ORDER BY nome");
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array('id' => (int)$rs->fields['id'], 'nome' => $rs->fields['nome']);
                $rs->MoveNext();
            }
        }
        return $out;
    }
}

?>