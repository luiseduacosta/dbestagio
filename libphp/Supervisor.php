<?php

require_once __DIR__ . '/Model.php';

/**
 * Supervisor — Model da tabela `supervisores`.
 *
 * Colunas conforme libphp/estagio.sql: id (auto), nome, cpf, email, telefone,
 *          celular, cress (INT), regiao (TINYINT), endereco, bairro, municipio,
 *          cep, escola, ano_formatura, cargo, observacoes, user_id.
 * Relação: instituições via tabela ponte `inst_super` (supervisor_id, instituicao_id).
 */
class Supervisor extends ADODB_Model {
    protected static $table  = 'supervisores';
    protected static $pk     = 'id';

    // Lista de colunas que podem ser gravadas/atualizadas.
    private static $colunas = array(
        'nome', 'cpf', 'email', 'telefone', 'celular', 'cress', 'regiao',
        'endereco', 'bairro', 'municipio', 'cep', 'escola', 'ano_formatura',
        'cargo', 'observacoes', 'user_id',
    );

    /**
     * Quantas instituições estão vinculadas a este supervisor (inst_super).
     */
    public function countInstituicoes() {
        $db = self::$db;
        return (int)$db->GetOne(
            "SELECT COUNT(*) FROM inst_super WHERE supervisor_id = ?",
            array($this->getKey())
        );
    }

    /**
     * Nomes das instituições vinculadas (para exibição / proteção de exclusão).
     */
    public function instituicoes() {
        $rs = static::query(
            "SELECT i.id, i.instituicao
             FROM instituicoes AS i
             INNER JOIN inst_super AS j ON j.instituicao_id = i.id
             WHERE j.supervisor_id = ?
             ORDER BY i.instituicao",
            array($this->getKey())
        );
        $out = array();
        if ($rs) {
            foreach ($rs as $row) {
                $out[] = array('id' => (int)$row->id, 'instituicao' => $row->instituicao);
            }
        }
        return $out;
    }

    /**
     * Vincula uma instituição ao supervisor.
     */
    public function vincularInstituicao($instituicao_id) {
        $instituicao_id = (int)$instituicao_id;
        if ($instituicao_id <= 0) return false;
        return static::query(
            "INSERT INTO inst_super (supervisor_id, instituicao_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE supervisor_id = supervisor_id",
            array($this->getKey(), $instituicao_id)
        );
    }

    /**
     * Desvincula uma instituição do supervisor.
     */
    public function desvincularInstituicao($instituicao_id) {
        $instituicao_id = (int)$instituicao_id;
        if ($instituicao_id <= 0) return false;
        return static::query(
            "DELETE FROM inst_super WHERE supervisor_id = ? AND instituicao_id = ?",
            array($this->getKey(), $instituicao_id)
        );
    }

    /**
     * Quantos estagiários estão vinculados a este supervisor.
     */
    public function countEstagiarios() {
        $db = self::$db;
        return (int)$db->GetOne(
            "SELECT COUNT(DISTINCT registro) FROM estagiarios WHERE supervisor_id = ?",
            array($this->getKey())
        );
    }

    /**
     * Preenche apenas as colunas persistentes da tabela supervisores.
     * A gravação é realizada posteriormente por save().
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
     * Lista de supervisores (ordenada) para o seletor/índice.
     */
    public static function listar($ordem = 'nome') {
        $colunas_validas = array('nome', 'cress', 'email', 'id');
        if (!in_array($ordem, $colunas_validas, true)) {
            $ordem = 'nome';
        }
        $rs = static::query("SELECT * FROM supervisores ORDER BY " . $ordem);
        $out = array();
        if ($rs) {
            foreach ($rs as $row) {
                $out[] = $row->toArray();
            }
        }
        return $out;
    }
}

?>