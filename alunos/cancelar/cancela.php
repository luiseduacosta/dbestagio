<?php

include_once("../../autentica.inc");

$aluno_id = isset($_REQUEST['aluno_id']) ? $_REQUEST['aluno_id'] : NULL;
$registro = isset($_REQUEST['registro']) ? $_REQUEST['registro'] : NULL;

if (empty($aluno_id)) {
	$sql = "select id from alunos where registro=$registro";
	$resultado = $db->Execute($sql);
	$aluno_id = $resultado->fields['id'];
}

if ($aluno_id) {
	$sql_estagiario = "select * from estagiarios where aluno_id='$aluno_id'";
	// echo $sql_estagiario . "<br>";
} else {
	exit;
}

$resultado_sql_estagiario = $db->Execute($sql_estagiario);
if ($resultado_sql_estagiario === false) die ("Não foi possível consultar a tabela estagiarios");
$quantidade = $resultado_sql_estagiario->RecordCount();

if ($quantidade === 0) {
	// die("Registro sera excluido");
	$sql_cancela_aluno = "delete from alunos where id='$aluno_id'";
	// echo $sql_cancela_aluno . "<br>";

	$resultado_cancela_aluno = $db->Execute($sql_cancela_aluno);
	if ($resultado_cancela_aluno === false) die ("Não foi possível cancelar o registro do aluno");
	header("Location:../exibir/listar.php");
} else {
	// echo "Existem estagios relacionados com este aluno. <br>Exclua primeiro os estagios para logo poder excluir o aluno";
	header("Location:ver_cancela.php?aluno_id=$aluno_id&erro=0");
}

exit;

?>
