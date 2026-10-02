<?php

require_once __DIR__ . '/Model.php';

/**
 * Supervisor — Model da tabela `supervisores`.
 *
 * Colunas: id (auto), nome, cpf, codigo_telefone, telefone, codigo_celular, celular,
 *          email, escola, ano_formacao, cress, regiao, cargo, observacoes,
 *          user_id, estagiarios_count.
 * Relação: instituições via tabela ponte `inst_super` (supervisor_id, instituicao_id).
 */
class Supervisor extends ADODB_Model {
    protected static $table  = 'supervisores';
    protected static $pk     = 'id';

    // Lista de colunas que podem ser gravadas/atualizadas.
    private static $colunas = array(
        'nome', 'cpf', 'codigo_telefone', 'telefone', 'codigo_celular', 'celular',
        'email', 'escola', 'ano_formacao', 'cress', 'regiao', 'cargo',
        'observacoes', 'user_id', 'estagiarios_count',
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
        $db = self::$db;
        $rs = $db->Execute(
            "SELECT i.id, i.instituicao
             FROM instituicoes AS i
             INNER JOIN inst_super AS j ON j.instituicao_id = i.id
             WHERE j.supervisor_id = ?
             ORDER BY i.instituicao",
            array($this->getKey())
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
     * Constrói uma query parametrizada simples com base em um array associativo.
     * Retorna true em caso de sucesso.
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
        $db = self::$db;
        $colunas_validas = array('nome', 'cress', 'email', 'id');
        if (!in_array($ordem, $colunas_validas, true)) {
            $ordem = 'nome';
        }
        $rs = $db->Execute("SELECT * FROM supervisores ORDER BY " . $ordem);
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = $rs->fields;
                $rs->MoveNext();
            }
        }
        return $out;
    }
}

?>