<?php

include_once("../setup.php");

$muralestagio_id = $_REQUEST['muralestagio_id'];

$sql_inscritos = "select id from inscricoes where muralestagio_id=$muralestagio_id";
$resultado_inscritos = $db->Execute($sql_inscritos);
// echo $sql_inscritos . "<br>";
if ($resultado_inscritos === false) die ("Não foi possível consultar a tabela inscricoes");
$quantidade = $resultado_inscritos->RecordCount();
// echo $quantidade . "<br>";

if ($quantidade === 0) {
	// Elimino o registro da instituicao
	$sql_estagio = "delete from mural_estagios where id='$muralestagio_id'";
	// echo $sql_estagio . "<br>";
	$resultado_estagio = $db->Execute($sql_estagio);
	if ($resultado_estagio === false) die ("Não foi possível excluir o registro da tabela mural_estagios");
} else {
	echo "Primeiro tem que excluir os alunos inscritos para logo poder excluir a instituição" . "<br>";
}

header("Location: ver-mural.php");

exit;

?>