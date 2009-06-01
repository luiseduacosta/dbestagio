<?php

include("../db.inc");
// Pego o nume do usuario e a senha enviado pelo login.php
$usuario_digitado = $_POST['usuario_nome'];
$senha_digitada   = $_POST['usuario_senha'];
// echo $usuario_digitado . " " . $senha_digitada . "<br>";
// Si não existe va para o login.php
if(empty($usuario_digitado) or (empty($senha_digitada))) {
    header("Location: login.php");
}
// Si existem busco na tabela de usuarios se estão autorizados
else
{
    // include("../db.inc");
    $sql = "select usuario, senha from usuarios where usuario='$usuario_digitado' and senha='$senha_digitada'";
    $resultado = $db->Execute($sql);
    if($resultado === false) die ("Não foi possível consultar a tabela usuarios");
    $quantidade = $resultado->RecordCount();

    while(!$resultado->EOF) {
	$db_usuario = $resultado->fields["usuario"];
	$db_senha   = $resultado->fields["senha"];
	$resultado->MoveNext();
    }
    $usuario_senha_passw = crypt(chop($db_senha),post);
    $usuario_senha_passw = substr($usuario_senha_passw,4);
    // echo "Senha digitada= " . $senha_digitada . " Dbase senha= ". $db_senha . "<br>";
    // Si a senha digitada conicide com a senha da tabela envio o cookie 
    // e abro a capa_logado.php
    if($senha_digitada == $db_senha)
    {
	setcookie("mural_usuario",$usuario_digitado);
	setcookie("mural_senha",$usuario_senha_passw);
	// header("Location: http://www.locuss.org/estagio/capa_logado.php?usuario_nome=$usuario_digitado");
	header("Location: ver-mural.php");
    }
    // Caso contrário retorna para o login.php
    else
    {
	header("Location: login.php");
    }
}

?>