<?php

$usuario_nome  = $_COOKIE['usuario_nome'];
$usuario_senha = $_COOKIE['usuario_senha'];

$http_host = $_SERVER['HTTP_HOST'];
// echo "Servidor " . $http_host;
$url = $_SERVER['SERVER_NAME'];

if(empty($usuario_nome) or (empty($usuario_senha))) {
	$mural_autentica = 0;
	header("Location: http://$url/estagio/login.php");
} else {
	$mural_autentica = 1;
}

?>