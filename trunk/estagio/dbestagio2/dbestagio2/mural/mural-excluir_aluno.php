<?php

$id_aluno = isset($_REQUEST['id_aluno']) ? $_REQUEST['id_aluno']: NULL;
$registro = isset($_REQUEST['registro']) ? $_REQUEST['registro']: NULL;

include_once("../db.inc");

$sql = "select id, registro from alunosNovos where id='$id_aluno'";
// echo $sql . "<br>";
$res = $db->Execute($sql);
$quantidade_aluno = $res->RecordCount();
if ($quantidade_aluno > 0) {
	// Excluo o aluno ...
	$sql = "delete from alunosNovos where id = '$id_aluno'";
	// echo $sql . "<br>";
	$resultado = $db->Execute($sql);
	if ($resultado === false) die("Não foi possível excluir o registro da tabela alunosNovos");
}

$sql_inscricao = "select id, id_aluno from mural_inscricao where id_aluno = '$registro'";
// echo $sql_inscricao . "<br>";
$res_inscricao = $db->Execute($sql_inscricao);
$quantidade = $res_inscricao->RecordCount();
if ($quantidade > 0) {
	// e também as inscricoes realizadas
	$sql_mural = "delete from mural_inscricao where id_aluno = '$registro'";
	// echo $sql_mural . "<br>";
	$res_mural = $db->Execute($sql_mural);
	if ($res_mural === false) die ("Não foi possível excluir os registros na tabela mural_inscricao");
}

header("Location:lista-alunos.php");

?>