<?php
/**
 * users/registrar.php — Passo 1 do cadastro de novo usuário.
 *
 * Fluxo:
 *   GET  → exibe formulário (e-mail, senha, role: aluno/professor/supervisor)
 *   POST → valida, cria o user (ativo=1), redireciona para o passo 2
 *          (registrar_entidade.php) onde o usuário vincula ou cria
 *          a entidade correspondente (aluno/professor/supervisor).
 *
 * Regras de segurança:
 *   - Nunca permite role=admin (apenas admins existentes podem criar admins)
 *   - Senha hasheada com password_hash(PASSWORD_DEFAULT)
 *   - E-mail único (validado antes de inserir)
 *   - Consultas parametrizadas via ADOdb
 */
require_once(__DIR__ . "/../setup.php");
require_once(__DIR__ . "/../libphp/models.php");

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = trim($_POST['email'] ?? '');
    $password   = $_POST['password'] ?? '';
    $password2  = $_POST['password2'] ?? '';
    $role       = $_POST['role'] ?? '';
    $nome       = trim($_POST['nome'] ?? '');

    // Whitelist: só aluno/professor/supervisor — nunca admin
    $roles_permitidos = array('aluno', 'professor', 'supervisor');
    if (!in_array($role, $roles_permitidos, true)) {
        $erro = "Tipo de usuário inválido. Selecione Aluno, Professor ou Supervisor.";
    } elseif ($email === '' || $password === '' || $nome === '') {
        $erro = "Preencha todos os campos obrigatórios.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "E-mail inválido.";
    } elseif (strlen($password) < 6) {
        $erro = "A senha deve ter no mínimo 6 caracteres.";
    } elseif ($password !== $password2) {
        $erro = "As senhas não conferem.";
    } else {
        // Verifica e-mail único
        $existe = User::byEmail($email);
        if ($existe) {
            $erro = "Já existe um usuário cadastrado com este e-mail.";
        } else {
            // Mapeia role → categoria
            $categoria_map = array(
                'aluno'      => 2,
                'professor'  => 3,
                'supervisor' => 4,
            );

            // User::create() faz o hash da senha internamente e seta ativo=1
            $novo_user = User::create($email, $password, array(
                'nome'      => $nome,
                'role'      => $role,
                'categoria' => $categoria_map[$role],
            ));

            if ($novo_user) {
                $user_id = (int)$novo_user->getKey();
                if ($user_id > 0) {
                    // Redireciona para o passo 2
                    header("Location: registrar_entidade.php?user_id=" . $user_id);
                    exit;
                }
            }
            $erro = "Erro ao criar o usuário. Tente novamente.";
        }
    }
}

// Template
$smarty = new Smarty_estagio;
$smarty->assign('erro', $erro);
$smarty->display('users_registrar.tpl');
