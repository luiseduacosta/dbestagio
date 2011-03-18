<?php

include_once("mural-autentica.inc");

include_once("../setup.php");

$id_instituicao = $_REQUEST['id_instituicao'];

$sql_inscritos = "select id from mural_inscricao where id_instituicao=$id_instituicao";
$resultado_inscritos = $db->Execute($sql_inscritos);
if($resultado_inscritos === false) die ("Não foi possível consultar a tabela mural_inscricao");
$quantidade = $resultado_inscritos->RecordCount();
// echo $quantidade . "<br>";

if($quantidade === 0) {
	// Elimino o registro da instituicao
	$sql_estagio = "delete from mural_estagio where id='$id_instituicao'";
	// echo $sql_estagio . "<br>";
	$resultado_estagio = $db->Execute($sql_estagio);
	if($resultado_estagio === false) die ("Não foi possível excluir o registro da tabela mural_estagio");
} else {
	echo "Primeiro tem que excluir os alunos inscritos para logo poder excluir a instituição" . "<br>";
}

header("Location: ver-mural.php");

exit;

?>