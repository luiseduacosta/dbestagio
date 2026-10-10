<?php

require_once __DIR__ . '/Model.php';

/**
 * User — Model da tabela `users` (usuários do sistema de estágio).
 *
 * Esquema real (MariaDB): id, email, password, nome, role, categoria,
 * identificacao, entidade_id, ativo, criado_em, atualizado_em,
 * aluno_id, supervisor_id, professor_id.
 *
 * `password` é char(80) — cabe perfeitamente um hash bcrypt (60 chars).
 * `criado_em` e `atualizado_em` são gerenciados pelo proprio MySQL
 * (default CURRENT_TIMESTAMP), por isso $timestamps = false.
 */
class User extends ADODB_Model {
    protected static $table    = 'users';
    protected static $pk       = 'id';
    protected static $hidden   = array('password');
    protected static $timestamps = false; // MySQL já atualiza criado_em/atualizado_em

    /**
     * Busca usuário pelo e-mail.
     */
    public static function byEmail($email) {
        return static::firstWhere('email', $email);
    }

    /**
     * Verifica se a senha digitada corresponde ao hash armazenado.
     *
     * Suporta formatos ___________________________________________
     *  - bcrypt ($2y$) : PHP password_verify (formato moderno e seguro)
     *  - SHA-1 puro    : 40 caracteres hexadecimais (formato legado atual)
     *  - MD5 puro      : 32 caracteres hexadecimais (legado)
     *  - crypt()       : $1$, $5$, $6$, DES (legado)
     *
     * Se a senha estiver em formato legado (SHA-1/MD5/crypt) e bater,
     * o hash e atualizado automaticamente para bcrypt (self-upgrade),
     * de forma que no proximo login o formato ja e seguro.
     */
    public function verifyPassword($plainPassword) {
        $hash = isset($this->_data['password']) ? trim($this->_data['password']) : '';
        if ($hash === '') {
            return false;
        }

        $isLegacy = false;

        // 1) bcrypt (e outros formatos do password_verify, ex.: ar
        if (password_verify($plainPassword, $hash)) {
            if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
                $this->setPassword($plainPassword);
                $this->save();
            }
            return true;
        }

        // 2) SHA-1 puro (40 hex) - formato atual dos usuarios
        if (preg_match('/^[a-f0-9]{40}$/i', $hash)) {
            if (strtolower(hash('sha1', $plainPassword)) === strtolower($hash)) {
                $isLegacy = true;
            }
        }

        // 3) MD5 puro (32 hex)
        if (!$isLegacy && preg_match('/^[a-f0-9]{32}$/i', $hash)) {
            if (strtolower(md5($plainPassword)) === strtolower($hash)) {
                $isLegacy = true;
            }
        }

        // 4) crypt() com salt embutido ($1$, $5$, $6$, DES, etc.)
        $crypt_check = @crypt($plainPassword, $hash);
        if (!$isLegacy && is_string($hash) && $crypt_check !== '' &&
            $crypt_check !== '*' && $crypt_check === $hash) {
            $isLegacy = true;
        }

        // Senha legada correta -> migra para bcrypt imediatamente
        if ($isLegacy) {
            $this->setPassword($plainPassword);
            $this->save();
            return true;
        }

        return false;
    }

    /**
     * Define a senha em texto claro; grava como hash bcrypt.
     */
    public function setPassword($plainPassword) {
        $this->_data['password'] = password_hash($plainPassword, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Atalho: cria um novo usuário com e-mail e senha criptografada.
     */
    public static function create($email, $plainPassword, $extra = array()) {
        $user = new static();
        $user->email = $email;
        $user->password = password_hash($plainPassword, PASSWORD_DEFAULT);
        $user->ativo = 1;
        foreach ($extra as $col => $val) {
            $user->$col = $val;
        }
        return $user->save() ? $user : null;
    }

    /*
     * Ver cada usuário com informações completas:
     *   - todos os campos da tabela users,
     *   - role_texto / categoria_texto,
     *   - nome do aluno, supervisor e professor associados (via LEFT JOIN),
     *   - linhas completas das tabelas alunos / supervisores / professores
     *     quando o respectivo *_id estiver preenchido.
     *
     * Retorna um array associativo ou null quando o usuário não existe.
     */
    public static function verCadaCompleto($id) {
        $db = static::db();

        $sql = "SELECT u.*, "
             . "COALESCE(al.nome,  '') AS aluno_nome, "
             . "COALESCE(sup.nome, '') AS supervisor_nome, "
             . "COALESCE(prof.nome,'') AS professor_nome "
             . "FROM users AS u "
             . "LEFT JOIN alunos AS al       ON al.id        = u.aluno_id "
             . "LEFT JOIN supervisores AS sup ON sup.id      = u.supervisor_id "
             . "LEFT JOIN professores AS prof ON prof.id     = u.professor_id "
             . "WHERE u.id = ? LIMIT 1";
        $rs = $db->Execute($sql, array((int)$id));

        if ($rs === false || $rs->RecordCount() == 0) {
            return null;
        }

        $f = $rs->fields;

        $hidden = isset(static::$hidden) && is_array(static::$hidden) ? static::$hidden : array();
        foreach ($hidden as $h) {
            if (array_key_exists($h, $f)) {
                unset($f[$h]);
            }
        }

        $out = $f;
        $out['id']              = (int)$out['id'];
        $out['role_texto']      = self::roleTexto($out['role']);
        $out['categoria_texto'] = self::categoriaTexto($out['categoria']);
        $out['ativo_raw']       = (int)$out['ativo'];

        $out['aluno']      = null;
        $out['supervisor'] = null;
        $out['professor']  = null;

        if (!empty($f['aluno_id'])) {
            $rsA = $db->Execute("SELECT * FROM alunos WHERE id = ? LIMIT 1", array((int)$f['aluno_id']));
            if ($rsA && $rsA->RecordCount() > 0) {
                $out['aluno'] = $rsA->fields;
            }
        }

        if (!empty($f['supervisor_id'])) {
            $rsS = $db->Execute("SELECT * FROM supervisores WHERE id = ? LIMIT 1", array((int)$f['supervisor_id']));
            if ($rsS && $rsS->RecordCount() > 0) {
                $out['supervisor'] = $rsS->fields;
            }
        }

        if (!empty($f['professor_id'])) {
            $rsP = $db->Execute("SELECT * FROM professores WHERE id = ? LIMIT 1", array((int)$f['professor_id']));
            if ($rsP && $rsP->RecordCount() > 0) {
                $out['professor'] = $rsP->fields;
            }
        }

        return $out;
    }

    /**
     * Lista os usuários com os campos formatados para exibição (listagem).
     */
    public static function listar() {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT u.*, "
            . "COALESCE(al.nome, '') AS aluno_nome, "
            . "COALESCE(sup.nome, '') AS supervisor_nome, "
            . "COALESCE(prof.nome, '') AS professor_nome "
            . "FROM users AS u "
            . "LEFT JOIN alunos AS al      ON al.id        = u.aluno_id "
            . "LEFT JOIN supervisores AS sup ON sup.id      = u.supervisor_id "
            . "LEFT JOIN professores AS prof ON prof.id     = u.professor_id "
            . "ORDER BY u.nome"
        );
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $f = $rs->fields;
                $out[] = array(
                    'id'              => (int)$f['id'],
                    'email'           => $f['email'],
                    'nome'            => $f['nome'],
                    'role'            => self::roleTexto($f['role']),
                    'categoria'       => self::categoriaTexto($f['categoria']),
                    'identificacao'   => $f['identificacao'],
                    'ativo'           => $f['ativo'],
                    'role_raw'        => $f['role'],
                    'ativo_raw'       => (int)$f['ativo'],
                    'aluno'           => $f['aluno_nome'],
                    'aluno_id'        => isset($f['aluno_id'])        ? (int)$f['aluno_id']        : null,
                    'supervisor'      => $f['supervisor_nome'],
                    'supervisor_id'   => isset($f['supervisor_id'])   ? (int)$f['supervisor_id']   : null,
                    'professor'       => $f['professor_nome'],
                    'professor_id'    => isset($f['professor_id'])    ? (int)$f['professor_id']    : null,
                );
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Rótulo legível do papel (role) do usuário.
     */
    public static function roleTexto($role) {
        switch ($role) {
            case 'admin':      return 'Administrador';
            case 'supervisor': return 'Supervisor';
            case 'professor':  return 'Professor';
            case 'aluno':      return 'Aluno';
            default:           return $role;
        }
    }

    /**
     * Rótulo legível da categoria (1-4).
     */
    public static function categoriaTexto($categoria) {
        switch ($categoria) {
            case '1': return 'Categoria 1';
            case '2': return 'Categoria 2';
            case '3': return 'Categoria 3';
            case '4': return 'Categoria 4';
            default:  return $categoria;
        }
    }
}

?>