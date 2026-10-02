<?php
/**
 * Helper compartilhado do módulo de usuários.
 * Lê e valida os campos do formulário (POST), devolvendo um array pronto
 * para atribuir ao modelo User. Usado por inserir.php e atualiza.php.
 *
 * A senha é tratada de forma diferente entre inserção (obrigatória) e
 * edição (opcional: só atualiza se preenchida).
 */
function users_validar_post($senhaObrigatoria = false) {
    $email   = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
    $nome    = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $role    = isset($_POST['role']) ? trim($_POST['role']) : '';
    $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : '';
    $identificacao = isset($_POST['identificacao']) ? trim($_POST['identificacao']) : '';
    $ativo   = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 1;
    $senha   = isset($_POST['password']) ? $_POST['password'] : '';

    // Caixa fechada de valores válidos.
    $roles = array('admin', 'supervisor', 'professor', 'aluno');
    $categorias = array('1', '2', '3', '4');

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Informe um e-mail válido.");
    }
    if ($nome === '') {
        die("Informe o nome do usuário.");
    }
    if (!in_array($role, $roles, true)) {
        die("Categoria (role) inválida.");
    }
    if (!in_array($categoria, $categorias, true)) {
        die("Categoria (1-4) inválida.");
    }
    if ($identificacao === '') {
        $identificacao = null;
    }
    if (!in_array($ativo, array(0, 1), true)) {
        $ativo = 1;
    }
    if ($senhaObrigatoria && $senha === '') {
        die("Informe uma senha.");
    }
    if ($senha !== '' && mb_strlen($senha, 'UTF-8') < 6) {
        die("A senha deve ter ao menos 6 caracteres.");
    }

    return array(
        'email'         => $email,
        'nome'          => $nome,
        'role'          => $role,
        'categoria'     => $categoria,
        'identificacao' => $identificacao,
        'ativo'         => $ativo,
        'password'      => $senha,
    );
}

/** Opções estáticas dos formulários de usuário. */
function users_form_options() {
    return array(
        'roles' => array(
            'admin'      => 'Administrador',
            'supervisor' => 'Supervisor',
            'professor'  => 'Professor',
            'aluno'      => 'Aluno',
        ),
        'categorias' => array(
            '1' => 'Categoria 1',
            '2' => 'Categoria 2',
            '3' => 'Categoria 3',
            '4' => 'Categoria 4',
        ),
        'ativo_opcoes' => array(
            '1' => 'Ativo',
            '0' => 'Inativo',
        ),
    );
}
?>