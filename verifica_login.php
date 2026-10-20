<?php

require_once("setup.php");
require_once("libphp/models.php");
require_once("libphp/sessao.php");

$email_digitado = isset($_POST['email']) ? trim($_POST['email']) : '';
$password_digitada = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($email_digitado) || empty($password_digitada)) {
    header("Location: login.php");
    exit;
}

// Busca o usuário pelo e-mail usando o modelo (tabela `users`).
$usuario = User::byEmail($email_digitado);

// Redireciona de volta ao login sem refletir nada do que o usuário digitou
// (evita XSS e evita que a senha/e-mail apareçam na URL).
function volta_ao_login() {
    echo("<script language='javascript'>window.top.location.href='index.php'</script>");
    exit;
}

if ($usuario === null) {
    volta_ao_login();
}

// DEBUG temporario (remover apos funcionar):
// echo "Email: " . htmlspecialchars($usuario->email) . "<br>";
// echo "Hash DB: " . htmlspecialchars($usuario->password) . "<br>";
// echo "MD5(digitada): " . md5($password_digitada) . "<br>";
// echo "SHA1(digitada): " . sha1($password_digitada) . "<br>";
// echo "crypt(digitada, hashDB): " . crypt($password_digitada, $usuario->password) . "<br>";
// echo "password_verify: " . (password_verify($password_digitada, $usuario->password) ? "SIM" : "NAO") . "<br>";
// exit;

$senha_ok = $usuario->verifyPassword($password_digitada);

if ($senha_ok) {
    // A identidade passa a viver na sessão (cookie de sessão httponly/samesite).
    // Os cookies de nome/email gravados aqui servem apenas para os menus em
    // JavaScript — nunca para autorizar.
    sessao_autenticar($usuario);

    // Normaliza role (compat com códigos numéricos legados)
    $role = (string)$usuario->role;
    $role_map = array('1' => 'admin', '2' => 'aluno', '3' => 'professor', '4' => 'supervisor');
    if (isset($role_map[$role])) {
        $role = $role_map[$role];
    }

    // Redireciona para a ficha da entidade correspondente:
    //   aluno      → alunos/ver_cada.php?aluno_id=xx
    //   professor  → professores/ver_cada.php?professor_id=xx
    //   supervisor → supervisores/ver_cada.php?supervisor_id=xx
    // Se o usuário ainda não tem entidade vinculada, vai completar o cadastro.
    // Admin cai na página principal.
    if ($role === 'aluno' && !empty($usuario->aluno_id)) {
        $destino = "alunos/ver_cada.php?aluno_id=" . (int)$usuario->aluno_id;
    } elseif ($role === 'professor' && !empty($usuario->professor_id)) {
        $destino = "professores/ver_cada.php?professor_id=" . (int)$usuario->professor_id;
    } elseif ($role === 'supervisor' && !empty($usuario->supervisor_id)) {
        $destino = "supervisores/ver_cada.php?supervisor_id=" . (int)$usuario->supervisor_id;
    } elseif (in_array($role, array('aluno', 'professor', 'supervisor'), true)) {
        $destino = "users/registrar_entidade.php?user_id=" . (int)$usuario->id;
    } else {
        $destino = "index.php";
    }

    echo("<script language='javascript'>window.top.location.href='" . $destino . "'</script>");
} else {
    volta_ao_login();
}

?>
