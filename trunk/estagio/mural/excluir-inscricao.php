<?php

// include_once("mural-autentica.inc");

include_once("../setup.php");

$id = $_REQUEST['id'];
$instituicao = $_REQUEST['instituicao'];
$id_instituicao = $_REQUEST['id_instituicao'];

$sql = "delete from mural_inscricao where id='$id'";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível excluir o registo da tabela mural_inscricao");

header("Location:listaInscritos.php?id_instituicao=$id_instituicao&instituicao=$instituicao");

?>
