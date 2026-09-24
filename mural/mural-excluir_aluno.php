<?php

$id_aluno = isset($_REQUEST['aluno_id']) ? $_REQUEST['aluno_id']: NULL;
$registro = isset($_REQUEST['registro']) ? $_REQUEST['registro']: NULL;

include_once("../setup.php");

// Excluo o aluno
$sql = "select id, registro from alunos where id='$id_aluno'";
// echo $sql . "<br>";
$res = $db->Execute($sql);
$quantidade_aluno = $res->RecordCount();
if ($quantidade_aluno > 0) {
	// Excluo o aluno ...
	$sql = "delete from alunos where id = '$id_aluno'";
	// echo $sql . "<br>";
	$resultado = $db->Execute($sql);
	if ($resultado === false) die("Não foi possível excluir o registro da tabela alunos");
}

// Excluo as inscrições
$sql_inscricao = "select id, registro from inscricoes where registro = '$registro'";
// echo $sql_inscricao . "<br>";
$res_inscricao = $db->Execute($sql_inscricao);
$quantidade = $res_inscricao->RecordCount();
if ($quantidade > 0) {
	// e tamb�m as inscricoes realizadas
	$sql_mural = "delete from inscricoes where registro = '$registro'";
	// echo $sql_mural . "<br>";
	$res_mural = $db->Execute($sql_mural);
	if ($res_mural === false) die ("Não foi possível excluir os registros na tabela inscricoes");
}

header("Location:lista-alunos.php");

?>