<?php

include_once("../setup.php");

$id = $_REQUEST['id'];
$instituicao = $_REQUEST['instituicao'];
$muralestagio_id = $_REQUEST['muralestagio_id'];

$sql = "delete from inscricoes where id='$id'";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível excluir o registo da tabela inscricoes");

header("Location:listaInscritos.php?muralestagio_id=$muralestagio_id&instituicao=$instituicao");

?>
