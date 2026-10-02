<?php

require_once __DIR__ . '/Model.php';

/**
 * Area — Model da tabela `areas`.
 *
 * Colunas: id (int), area (varchar 90).
 * Relação: instituicoes.area -> areas.id (uma área tem várias instituições).
 */
class Area extends ADODB_Model {
    protected static $table  = 'areas';
    protected static $pk     = 'id';

    /**
     * Quantidade de instituições que usam esta área.
     */
    public function countInstituicoes() {
        $db = self::$db;
        return (int)$db->GetOne("SELECT COUNT(*) FROM instituicoes WHERE area = ?", array($this->getKey()));
    }

    /**
     * Instituições vinculadas a esta área.
     */
    public function instituicoes() {
        $db = self::$db;
        $rs = $db->Execute(
            "SELECT e.id, e.instituicao, e.endereco, e.telefone, e.beneficios AS bolsa,
                    MAX(t.periodo) AS turma,
                    COALESCE(iss.q_super, 0) AS q_super
             FROM instituicoes AS e
             LEFT JOIN estagiarios AS t ON e.id = t.instituicao_id
             LEFT JOIN (
                 SELECT instituicao_id, COUNT(*) AS q_super
                 FROM inst_super
                 GROUP BY instituicao_id
             ) iss ON iss.instituicao_id = e.id
             WHERE e.area = ?
             GROUP BY e.id, e.instituicao, e.area, e.beneficios, e.endereco, e.telefone, iss.q_super
             ORDER BY e.instituicao",
            array($this->getKey())
        );
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array(
                    'id'            => (int)$rs->fields['id'],
                    'instituicao'   => $rs->fields['instituicao'],
                    'bolsa'         => $rs->fields['bolsa'],
                    'endereco'      => $rs->fields['endereco'],
                    'telefone'      => $rs->fields['telefone'],
                    'turma'         => $rs->fields['turma'],
                    'q_supervisores'=> (int)$rs->fields['q_super'],
                );
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Todas as áreas ordenadas por nome, para o seletor.
     */
    public static function seleciona() {
        $db = self::$db;
        $rs = $db->Execute("SELECT id, area FROM areas ORDER BY area");
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array('id' => (int)$rs->fields['id'], 'area' => $rs->fields['area']);
                $rs->MoveNext();
            }
        }
        return $out;
    }
}

?>