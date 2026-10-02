<?php
/**
 * Model.php — Camada de abstração de tabelas sobre ADOdb.
 *
 * Permite trabalhar com "models" (objetos por registro) sem abandonar a
 * conexão $db já existente (mysqli/ADOdb). Nenhuma dependência externa.
 *
 * Uso:
 *   $aluno = Aluno::find(3);              // registro por chave
 *   $alunos = Aluno::where('curso', 'A'); // lista filtrada (array de modelos)
 *   $aluno->nome = "João";
 *   $aluno->save();                       // INSERT ou UPDATE
 *   $aluno->delete();                     // DELETE
 */

if (!class_exists('ADODB_Model', false)) {

class ADODB_Model {

    // Config para ser definida nas subclasses:
    protected static $table   = null;   // nome da tabela
    protected static $pk      = 'id';   // chave primária
    protected static $columns = array(); // lista opcional de colunas ('' = todas)
    protected static $hidden  = array(); // colunas omitidas em toArray() (ex.: senha)
    protected static $timestamps = false; // true = gerencia created_at/updated_at

    /** @var ADOConnection|null Referência global à conexão (definida em models.php). */
    public static $db = null;

    /** @var array Dados do registro atual. */
    protected $_data = array();

    /** @var bool Já existe no banco? */
    protected $_exists = false;

    // ------------------------------------------------------------------
    // Conexão
    // ------------------------------------------------------------------

    /**
     * Define a conexão ADOdb usada por todos os modelos.
     */
    public static function setDb($db) {
        static::$db = $db;
    }

    /**
     * Retorna o nome da tabela para a classe concreta.
     */
    public static function table() {
        $t = static::$table;
        if ($t === null) {
            $class = get_called_class();
            $t = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class));
            $t = rtrim($t, '_') . 's';
        }
        return $t;
    }

    /**
     * Retorna lista de colunas ('' significa 'SELECT *').
     */
    protected static function columns() {
        return empty(static::$columns) ? '*' : implode(',', array_map(function($c) {
            return "`" . trim($c, "`") . "`";
        }, static::$columns));
    }

    // ------------------------------------------------------------------
    // Query builders
    // ------------------------------------------------------------------

    /**
     * Localiza um registro pela chave primária.
     */
    public static function find($id) {
        $db = static::db();
        $sql = "SELECT " . static::columns() . " FROM " . static::table()
             . " WHERE " . static::$pk . " = ?";
        $rs = $db->Execute($sql, array($id));
        if ($rs === false || $rs->RecordCount() == 0) {
            return null;
        }
        $obj = new static();
        $obj->_data = $rs->fields;
        $obj->_exists = true;
        return $obj;
    }

    /**
     * Retorna o primeiro registro que satisfaz a condição WHERE.
     */
    public static function firstWhere($column, $value) {
        $db = static::db();
        $sql = "SELECT " . static::columns() . " FROM " . static::table()
             . " WHERE `$column` = ? LIMIT 1";
        $rs = $db->Execute($sql, array($value));
        if ($rs === false || $rs->RecordCount() == 0) {
            return null;
        }
        $obj = new static();
        $obj->_data = $rs->fields;
        $obj->_exists = true;
        return $obj;
    }

    /**
     * Retorna todos os registros que satisfazem WHERE (array de models).
     * Aceita array de pares coluna => valor:
     *   Model::where(array('curso' => 'A', 'ano' => 2024))
     */
    public static function where($conditions, $params = array()) {
        $db  = static::db();
        $sql = "SELECT " . static::columns() . " FROM " . static::table();

        if (is_array($conditions) && !count($conditions)) {
            $rs = $db->Execute($sql);
        } elseif (is_string($conditions)) {
            $sql .= " WHERE $conditions";
            $rs = $db->Execute($sql, $params);
        } else {
            $where = array();
            $vals  = array();
            foreach ($conditions as $col => $val) {
                $where[] = "`$col` = ?";
                $vals[]  = $val;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
            $rs = $db->Execute($sql, $vals);
        }

        return static::hydrate($rs);
    }

    /**
     * Retorna todos os registros da tabela (array de models).
     */
    public static function all() {
        return static::where(array());
    }

    /**
     * Executa SQL livre e retorna array de models.
     */
    public static function query($sql, $params = array()) {
        $db = static::db();
        $rs = $db->Execute($sql, $params);
        return static::hydrate($rs);
    }

    /**
     * Executa SQL livre e retorna o resultado ADOdb bruto.
     */
    public static function raw($sql, $params = array()) {
        return static::db()->Execute($sql, $params);
    }

    // ------------------------------------------------------------------
    // Internos
    // ------------------------------------------------------------------

    protected static function db() {
        if (static::$db === null) {
            throw new Exception('Conexão ADOdb não definida. Chame ' . get_called_class()
                . '::setDb($db) (ou inclua libphp/models.php) antes de usar o modelo.');
        }
        return static::$db;
    }

    protected static function hydrate($rs) {
        $out = array();
        if ($rs === false) return $out;
        while (!$rs->EOF) {
            $obj = new static();
            $obj->_data = $rs->fields;
            $obj->_exists = true;
            $out[] = $obj;
            $rs->MoveNext();
        }
        return $out;
    }

    // ------------------------------------------------------------------
    // API de instância
    // ------------------------------------------------------------------

    /**
     * Acesso mágico: $modelo->coluna
     */
    public function __get($name) {
        return array_key_exists($name, $this->_data) ? $this->_data[$name] : null;
    }

    /**
     * Acesso mágico: $modelo->coluna = valor
     */
    public function __set($name, $value) {
        $this->_data[$name] = $value;
    }

    public function __isset($name) {
        return array_key_exists($name, $this->_data) && $this->_data[$name] !== null;
    }

    /**
     * Retorna todos os dados como array (escondendo colunas ocultas, ex. senha).
     */
    public function toArray($visible = false) {
        $data = $this->_data;
        if ($visible) return $data;
        foreach (static::$hidden as $h) {
            unset($data[$h]);
        }
        return $data;
    }

    /**
     * Obtém o valor da chave primária.
     */
    public function getKey() {
        return isset($this->_data[static::$pk]) ? $this->_data[static::$pk] : null;
    }

    /**
     * Este registro já existe no banco?
     */
    public function exists() {
        return $this->_exists;
    }

    /**
     * Insere ou atualiza o registro conforme existência.
     * Retorna true em caso de sucesso.
     */
    public function save() {
        $db = static::db();

        if (static::$timestamps) {
            $now = date('Y-m-d H:i:s');
            if (!isset($this->_data['created_at'])) $this->_data['created_at'] = $now;
            $this->_data['updated_at'] = $now;
        }

        if ($this->_exists) {
            // UPDATE
            $key = $this->getKey();
            if ($key === null || $key === '') {
                throw new Exception('Não é possível atualizar um registro sem chave primária.');
            }
            $sets = array();
            $vals = array();
            foreach ($this->_data as $col => $val) {
                if ($col == static::$pk) continue;
                $sets[] = "`$col` = ?";
                $vals[] = $val;
            }
            if (count($sets) == 0) return true;
            $vals[] = $key;
            $sql = "UPDATE " . static::table() . " SET " . implode(', ', $sets)
                 . " WHERE " . static::$pk . " = ?";
            $ok = $db->Execute($sql, $vals);
            return $ok !== false;
        }

        // INSERT
        $cols = array_keys($this->_data);
        $vals = array_values($this->_data);
        $ph   = array_fill(0, count($cols), '?');
        $sql  = "INSERT INTO " . static::table()
              . " (`" . implode('`,`', $cols) . "`) VALUES (" . implode(',', $ph) . ")";
        $ok = $db->Execute($sql, $vals);
        if ($ok !== false && static::$pk) {
            $id = $db->Insert_ID();
            if ($id) $this->_data[static::$pk] = $id;
            $this->_exists = true;
        }
        return $ok !== false;
    }

    /**
     * Remove o registro do banco. Retorna true em caso de sucesso.
     */
    public function delete() {
        if (!$this->_exists) return false;
        $db  = static::db();
        $sql = "DELETE FROM " . static::table() . " WHERE " . static::$pk . " = ?";
        $ok  = $db->Execute($sql, array($this->getKey()));
        if ($ok !== false) {
            $this->_exists = false;
            return true;
        }
        return false;
    }

    /**
     * Contagem de registros que satisfazem WHERE.
     */
    public static function count($conditions = array()) {
        $db = static::db();
        $sql = "SELECT COUNT(*) AS cnt FROM " . static::table();
        $vals = array();
        if (is_array($conditions) && count($conditions)) {
            $where = array();
            foreach ($conditions as $col => $val) {
                $where[] = "`$col` = ?";
                $vals[]  = $val;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $rs = $db->Execute($sql, $vals);
        return ($rs !== false) ? (int)$rs->fields['cnt'] : 0;
    }

}

}

?>