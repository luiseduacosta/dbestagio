<?php

include_once("setup.php");

$email_digitado = isset($_POST['email']) ? trim($_POST['email']) : '';
$password_digitada = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($email_digitado) || empty($password_digitada)) {
    header("Location: login.php");
    exit;
}

// Consultar usuarios
$sql = "SELECT email, password FROM users WHERE email = '$email_digitado'";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela user");

if ($resultado->RecordCount() === 0) {
    echo("<script language='javascript'>parent.window.location.href='login.php?email=$email_digitado&password=$password_digitada&opcao=login&msg=Email ou senha inválidos'</script>");
    exit;
}

$db_email = $resultado->fields["email"];
$db_password_hash = $resultado->fields["password"];

function verificar_senha($senha_digitada, $hash_armazenado) {
    if (function_exists('password_verify') && strlen($hash_armazenado) > 50) {
        if (password_verify($senha_digitada, $hash_armazenado)) {
            return true;
        }
    }

    if (crypt($senha_digitada, $hash_armazenado) === $hash_armazenado) {
        return true;
    }

    if (preg_match('/^[a-f0-9]{32}$/i', $hash_armazenado)) {
        if (md5($senha_digitada) === strtolower($hash_armazenado)) {
            return true;
        }
    }

    if (preg_match('/^[a-f0-9]{40}$/i', $hash_armazenado)) {
        if (sha1($senha_digitada) === strtolower($hash_armazenado)) {
            return true;
        }
    }

    return false;
}

function converter_hash_para_bcrypt($senha_digitada, $hash_armazenado, $db, $email) {
    if (strlen($hash_armazenado) > 50 && strpos($hash_armazenado, '$2y$') === 0) {
        return false;
    }
    if (function_exists('password_verify')) {
        $novo_hash = password_hash($senha_digitada, PASSWORD_DEFAULT);
        if ($novo_hash) {
            $update_sql = "UPDATE users SET password = ? WHERE email = ?";
            $db->Execute($update_sql, array($novo_hash, $email));
            return true;
        }
    }
    return false;
}

$senha_ok = verificar_senha($password_digitada, $db_password_hash);

// echo "Digitada: " . htmlspecialchars($password_digitada) . "<br>";
// echo "Hash DB: " . htmlspecialchars($db_password_hash) . "<br>";
// echo "MD5(digitada): " . md5($password_digitada) . "<br>";
// echo "SHA1(digitada): " . sha1($password_digitada) . "<br>";
// echo "crypt(digitada, hashDB): " . crypt($password_digitada, $db_password_hash) . "<br>";
// echo "OK? " . ($senha_ok ? "SIM" : "NAO") . "<br>";
// exit;

if ($senha_ok) {
    converter_hash_para_bcrypt($password_digitada, $db_password_hash, $db, $db_email);
    setcookie("usuario", $db_email);
    echo("<script language='javascript'>parent.window.location.href='mural/ver-mural.php'</script>");
} else {
    echo("<script language='javascript'>parent.window.location.href='login.html'</script>");
}

?>
