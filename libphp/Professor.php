<?php

if (!class_exists('Professor', false)) {

/**
 * Professor — Model da tabela `professores`.
 *
 * Colunas: id (auto_increment), nome, cpf, siape, cress, regiao,
 *          codigo_telefone, telefone, codigo_celular, celular, email,
 *          curriculolattes, atualizacaolattes, dataingresso, tipocargo,
 *          departamento, dataegresso, motivoegresso, status, user_id,
 *          estagiarios_count, observacoes, created, modified.
 */
class Professor extends ADODB_Model {
    protected static $table  = 'professores';
    protected static $pk     = 'id';

    // Colunas graváveis via preencher()/save().
    private static $colunas = array(
        'nome', 'cpf', 'siape', 'cress', 'regiao',
        'codigo_telefone', 'telefone', 'codigo_celular', 'celular', 'email',
        'curriculolattes', 'atualizacaolattes', 'dataingresso', 'tipocargo',
        'departamento', 'dataegresso', 'motivoegresso', 'status',
        'user_id', 'estagiarios_count', 'observacoes',
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
     * Verifica se existe outro professor com o mesmo e-mail e/ou CPF.
     * Retorna o id do conflitante ou null. Aceita um id a ignorar (edição).
     */
    public static function duplicado($email, $cpf = null, $siape = null, $ignorarId = null) {
        $db = static::db();
        $cond  = array();
        $param = array();
        if ($email !== '') {
            $cond[]  = "LOWER(email) = ?";
            $param[] = strtolower($email);
        }
        if ($cpf !== '') {
            $cond[]  = "cpf = ?";
            $param[] = $cpf;
        }
        if ($siape !== '') {
            $cond[]  = "siape = ?";
            $param[] = $siape;
        }
        if (!$cond) {
            return null;
        }
        $sql = "SELECT id FROM professores WHERE (" . implode(" OR ", $cond) . ")";
        if ($ignorarId !== null) {
            $sql .= " AND id <> ?";
            $param[] = (int)$ignorarId;
        }
        $sql .= " LIMIT 1";
        $rs = $db->Execute($sql, $param);
        return ($rs !== false && $rs->RecordCount() > 0) ? (int)$rs->fields['id'] : null;
    }

    /**
     * Lista de professores para o índice (DataTables).
     *
     * @param string $busca Valor a pesquisar em nome/e-mail/CPF/siape.
     * @param string $status Filtra por status ('', 'ativo', 'inativo', 'aposentado').
     * @param string $orderby Coluna de ordenação (whitelist aplicada no chamador).
     */
    public static function listar($busca = '', $status = '', $orderby = 'p.nome') {
        $db = static::db();

        $sql = "SELECT p.*, "
             . "(SELECT COUNT(*) FROM estagiarios e WHERE e.professor_id = p.id) AS num_estagios "
             . "FROM professores p";

        $where  = array();
        $params = array();
        if ($busca !== '') {
            $where[]  = "(p.nome LIKE ? OR LOWER(p.email) LIKE ? OR p.cpf LIKE ? OR p.siape LIKE ?)";
            $params[] = "%$busca%";
            $params[] = "%" . strtolower($busca) . "%";
            $params[] = "%$busca%";
            $params[] = "%$busca%";
        }
        if ($status !== '') {
            $where[]  = "p.status = ?";
            $params[] = $status;
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY " . $orderby;

        $rs = $db->Execute($sql, $params);
        if ($rs === false) {
            die("Não foi possível consultar a tabela professores");
        }
        $out = array();
        while (!$rs->EOF) {
            $f = $rs->fields;
            $out[] = array(
                'id'              => (int)$f['id'],
                'nome'            => $f['nome'],
                'departamento'    => $f['departamento'],
                'email'           => strtolower((string)$f['email']),
                'telefone'        => self::formataTelefone($f['codigo_telefone'], $f['telefone']),
                'celular'         => self::formataTelefone($f['codigo_celular'], $f['celular']),
                'status'          => $f['status'],
                'num_estagios'    => (int)$f['num_estagios'],
            );
            $rs->MoveNext();
        }
        return $out;
    }

    /**
     * Busca um professor pelo id, já com a contagem de estagiários e instituições.
     */
    public static function buscar($id) {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT p.*, "
            . "(SELECT COUNT(*) FROM estagiarios e WHERE e.professor_id = p.id) AS num_estagios, "
            . "(SELECT COUNT(DISTINCT e.instituicao_id) FROM estagiarios e WHERE e.professor_id = p.id AND e.instituicao_id IS NOT NULL) AS num_instituicoes "
            . "FROM professores p WHERE p.id = ? LIMIT 1",
            array((int)$id)
        );
        if ($rs === false || $rs->RecordCount() == 0) {
            return null;
        }
        return $rs->fields;
    }

    /**
     * Estagiários vinculados ao professor, com instituição e período resolvidos
     * na própria consulta (sem N+1).
     */
    public static function estagiarios($professor_id, $ordem = 'e.periodo') {
        $db = static::db();
        $ordens_validas = array('e.periodo', 'a.nome', 'a.registro', 'i.instituicao');
        if (!in_array($ordem, $ordens_validas, true)) {
            $ordem = 'e.periodo';
        }
        $rs = $db->Execute(
            "SELECT e.id AS estagiario_id, a.id AS aluno_id, a.registro, a.nome AS aluno_nome, "
            . "e.periodo, e.nivel, i.id AS instituicao_id, i.instituicao, ar.area "
            . "FROM estagiarios e "
            . "JOIN alunos a ON a.id = e.aluno_id "
            . "LEFT JOIN instituicoes i ON i.id = e.instituicao_id "
            . "LEFT JOIN areas ar ON ar.id = i.area "
            . "WHERE e.professor_id = ? ORDER BY " . $ordem,
            array((int)$professor_id)
        );
        if ($rs === false) {
            return array();
        }
        $out = array();
        while (!$rs->EOF) {
            $out[] = array(
                'estagiario_id'   => (int)$rs->fields['estagiario_id'],
                'aluno_id'        => (int)$rs->fields['aluno_id'],
                'registro'        => (int)$rs->fields['registro'],
                'aluno_nome'      => $rs->fields['aluno_nome'],
                'periodo'         => $rs->fields['periodo'],
                'nivel'           => $rs->fields['nivel'],
                'instituicao_id'  => (int)$rs->fields['instituicao_id'],
                'instituicao'     => ($rs->fields['instituicao'] !== null) ? $rs->fields['instituicao'] : 'Sem dados',
                'area'            => $rs->fields['area'],
            );
            $rs->MoveNext();
        }
        return $out;
    }

    /**
     * Instituições com as quais o professor trabalha (sem N+1).
     */
    public static function instituicoes($professor_id) {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT DISTINCT i.id, i.instituicao "
            . "FROM instituicoes i "
            . "INNER JOIN estagiarios e ON e.instituicao_id = i.id "
            . "WHERE e.professor_id = ? AND e.instituicao_id IS NOT NULL "
            . "ORDER BY i.instituicao",
            array((int)$professor_id)
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
     * Lista simples (id + nome) ordenada por nome, para preencher seletores
     * nos formulários (ex.: cadastro de ofertas no mural).
     */
    public static function listaSimples() {
        $db = static::db();
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

    /**
     * Lista de status possíveis (para o filtro/select).
     */
    public static function statuses() {
        return array('' => 'Todos', 'ativo' => 'Ativo', 'inativo' => 'Inativo', 'aposentado' => 'Aposentado');
    }

    private static function formataTelefone($ddd, $numero) {
        if (empty($numero)) return '';
        $ddd = (int)$ddd ?: 21;
        return "($ddd) $numero";
    }
}

}

?>