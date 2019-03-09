<?php

$usuario_nome  = $_COOKIE['mural_usuario'];
$usuario_senha = $_COOKIE['mural_senha'];

// echo $mural_usuario;

// echo "Mural usuario" . $usuario_nome . "<br />";
// echo "Mural senha"   . $usuario_senha . "<br />";

if(empty($usuario_nome))
{
    // echo "Sem senha" . "<br>";
    header("Location:login.php");
    exit;
}

?>