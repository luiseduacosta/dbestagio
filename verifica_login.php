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
    echo("<script language='javascript'>window.top.location.href='index.php'</script>");
} else {
    volta_ao_login();
}

?>
