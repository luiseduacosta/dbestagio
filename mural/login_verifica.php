<?php

include("../setup.php");

$usuario_digitado = isset($_POST['usuario_nome']) ? trim($_POST['usuario_nome']) : '';
$senha_digitada   = isset($_POST['usuario_senha']) ? $_POST['usuario_senha'] : '';

if (empty($usuario_digitado) || empty($senha_digitada)) {
	header("Location: login.php");
	exit;
}

$sql = "select usuario, senha from usuarios where usuario = ?";
$resultado = $db->Execute($sql, array($usuario_digitado));
if ($resultado === false) die ("Não foi possível consultar a tabela usuarios");

if ($resultado->RecordCount() === 0) {
	header("Location: login.php");
	exit;
}

$db_usuario = $resultado->fields["usuario"];
$db_senha   = $resultado->fields["senha"];

function mural_verificar_senha($senha_digitada, $hash_armazenado) {
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

function mural_converter_bcrypt($senha_digitada, $hash_armazenado, $db, $usuario) {
	if (strlen($hash_armazenado) > 50 && strpos($hash_armazenado, '$2y$') === 0) {
		return false;
	}
	if (function_exists('password_verify')) {
		$novo_hash = password_hash($senha_digitada, PASSWORD_DEFAULT);
		if ($novo_hash) {
			$update_sql = "UPDATE usuarios SET senha = ? WHERE usuario = ?";
			$db->Execute($update_sql, array($novo_hash, $usuario));
			return true;
		}
	}
	return false;
}

$senha_ok = mural_verificar_senha($senha_digitada, $db_senha);

if ($senha_ok) {
	mural_converter_bcrypt($senha_digitada, $db_senha, $db, $db_usuario);
	setcookie("mural_usuario", $usuario_digitado);
	header("Location: ver-mural.php");
} else {
	header("Location: login.php");
}

?>
