<?php

// Usuário autenticado no sistema?
$usuario_nome  = $_COOKIE['usuario_nome'];
$usuario_senha = $_COOKIE['usuario_senha'];

$http_host = $_SERVER['HTTP_HOST'];
// echo "Servidor " . $http_host;
$url = $_SERVER['SERVER_NAME'];

if(empty($usuario_nome) or (empty($usuario_senha))) {
    // Enviar para login.php
    $sistema_autentica = 0;
} else {
    $sistema_autentica = 1;
}
// echo "Usuário autenticado no sistema? " . $sistema_autentica . "<br>";

// Usuário está autenticado no mural?
$usuario_mural = $_COOKIE['mural_usuario'];
$senha_mural   = $_COOKIE['mural_senha'];
if(empty($usuario_mural) or (empty($senha_mural))) {
    $mural_autentica = 0;
} else {
    $mural_autentica = 1;
}

// echo "Usuário autenticado no mural? " . $mural_autentica . "<br>";

?>
