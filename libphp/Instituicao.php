<?php

require_once __DIR__ . '/Model.php';

/**
 * Instituicao — Model da tabela `instituicoes`.
 *
 * Relações (via colunas/chaves existentes no banco):
 *  - areas        : instituicoes.area -> areas.id      (nitidez: campo `area` guarda id da area)
 *  - supervisores : tabela ponte `inst_super` (instituicao_id, supervisor_id)
 *  - visitas      : visitas.instituicao_id          -> instituicoes.id
 *  - mural_estagios: mural_estagios.instituicao_id  -> instituicoes.id
 *  - estagiarios  : estagiarios.instituicao_id      -> instituicoes.id
 */
class Instituicao extends ADODB_Model {
    protected static $table  = 'instituicoes';
    protected static $pk     = 'id';
    protected static $hidden = array();

    // ------------------------------------------------------------------
    // Área (tabela areas)
    // ------------------------------------------------------------------

    /**
     * Nome da área da instituição (via instituicoes.area -> areas.id).
     */
    public function areaNome() {
        if (empty($this->_data['area'])) {
            return '';
        }
        $db = self::$db;
        return $db->GetOne("SELECT area FROM areas WHERE id = ?", array($this->_data['area']));
    }

    /**
     * Lista de todas as áreas para o formulário (seletor).
     */
    public static function areasLista() {
        $db = self::$db;
        $rs = $db->Execute("SELECT id, area FROM areas ORDER BY area");
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array('id' => $rs->fields['id'], 'area' => $rs->fields['area']);
                $rs->MoveNext();
            }
        }
        return $out;
    }

    // ------------------------------------------------------------------
    // Supervisores (ponte inst_super)
    // ------------------------------------------------------------------

    /**
     * Supervisores vinculados à instituição (via inst_super).
     */
    public function supervisores() {
        $db = self::$db;
        $rs = $db->Execute(
            "SELECT s.id, s.nome, s.cress
             FROM supervisores AS s
             INNER JOIN inst_super AS j ON j.supervisor_id = s.id
             WHERE j.instituicao_id = ?
             ORDER BY s.nome",
            array($this->getKey())
        );
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array(
                    'supervisor_id' => $rs->fields['id'],
                    'nome'          => $rs->fields['nome'],
                    'cress'         => $rs->fields['cress'],
                );
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Quantos supervisores já registraram estágio nesta instituição (estagiarios).
     */
    public function countSupervisoresAtivos() {
        $db = self::$db;
        return (int)$db->GetOne(
            "SELECT COUNT(DISTINCT supervisor_id) FROM estagiarios WHERE instituicao_id = ?",
            array($this->getKey())
        );
    }

    /**
     * Todos os supervisores existentes (para o seletor de vínculo).
     */
    public static function supervisoresTodos() {
        $db = self::$db;
        $rs = $db->Execute("SELECT id, nome FROM supervisores ORDER BY nome");
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array('id' => $rs->fields['id'], 'nome' => $rs->fields['nome']);
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Vincula um supervisor à instituição (evita duplicata).
     */
    public function vincularSupervisor($supervisor_id) {
        $db = self::$db;
        $jaExiste = $db->GetOne(
            "SELECT COUNT(*) FROM inst_super WHERE instituicao_id = ? AND supervisor_id = ?",
            array($this->getKey(), $supervisor_id)
        );
        if ($jaExiste > 0) {
            return false; // já vinculado
        }
        return $db->Execute(
            "INSERT INTO inst_super (instituicao_id, supervisor_id) VALUES (?, ?)",
            array($this->getKey(), $supervisor_id)
        ) !== false;
    }

    /**
     * Desvincula um supervisor da instituição.
     */
    public function desvincularSupervisor($supervisor_id) {
        $db = self::$db;
        return $db->Execute(
            "DELETE FROM inst_super WHERE instituicao_id = ? AND supervisor_id = ?",
            array($this->getKey(), $supervisor_id)
        ) !== false;
    }

    /**
     * Conta registros de estagiarios vinculados à instituição (para proteger exclusão).
     */
    public function countEstagiarios() {
        $db = self::$db;
        return (int)$db->GetOne("SELECT COUNT(*) FROM estagiarios WHERE instituicao_id = ?", array($this->getKey()));
    }

    // ------------------------------------------------------------------
    // Visitas
    // ------------------------------------------------------------------

    /**
     * Visitas registradas para a instituição.
     */
    public function visitas() {
        $db = self::$db;
        $rs = $db->Execute(
            "SELECT v.*, p.nome AS professor_nome
             FROM visitas AS v
             LEFT JOIN professores AS p ON p.id = v.professor_id
             WHERE v.instituicao_id = ?
             ORDER BY v.data DESC",
            array($this->getKey())
        );
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = $rs->fields;
                $rs->MoveNext();
            }
        }
        return $out;
    }

    public function countVisitas() {
        return count($this->visitas());
    }

    // ------------------------------------------------------------------
    // Mural de estágios
    // ------------------------------------------------------------------

    /**
     * Registros de mural_estagios da instituição.
     */
    public function murais() {
        $db = self::$db;
        $rs = $db->Execute(
            "SELECT id, periodo, convenio, vagas FROM mural_estagios WHERE instituicao_id = ? ORDER BY periodo DESC",
            array($this->getKey())
        );
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = $rs->fields;
                $rs->MoveNext();
            }
        }
        return $out;
    }

    public function countMurais() {
        return count($this->murais());
    }

    // ------------------------------------------------------------------
    // CRUD comuns herdados de ADODB_Model: find, where, save, delete, count
    // ------------------------------------------------------------------

    /**
     * Lista simples das instituições (id + nome) ordenadas por nome,
     * para preencher seletores nos formulários.
     */
    public static function listar_todas() {
        $db = self::$db;
        $rs = $db->Execute("SELECT id, instituicao FROM instituicoes ORDER BY instituicao");
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array('id' => (int)$rs->fields['id'], 'instituicao' => $rs->fields['instituicao']);
                $rs->MoveNext();
            }
        }
        return $out;
    }
}

?>