<?php

include_once("../../autentica.inc");
include_once("../../setup.php");

$id_aluno = isset($_REQUEST['id_aluno']) ? $_REQUEST['id_aluno'] : NULL;
$registro = isset($_REQUEST['registro']) ? $_REQUEST['registro'] : NULL;

if (empty($id_aluno)) {
	$sql = "select id from alunos where registro=$registro";
	$resultado = $db->Execute($sql);
	$id_aluno = $resultado->fields['id'];
}

if ($id_aluno) {
	$sql_estagiario = "select * from estagiarios where id_aluno='$id_aluno'";
	// echo $sql_estagiario . "<br>";
} else {
	exit;
}

$resultado_sql_estagiario = $db->Execute($sql_estagiario);
if($resultado_sql_estagiario === false) die ("N�o foi poss�vel consultar a tabela estagiarios");
$quantidade = $resultado_sql_estagiario->RecordCount();

if($quantidade === 0) {
		$sql_cancela_aluno = "delete from alunos where id='$id_aluno'";
		// echo $sql_cancela_aluno . "<br>";

		$resultado_cancela_aluno = $db->Execute($sql_cancela_aluno);
		if($resultado_cancela_aluno === false) die ("N�o foi poss�vel cancelar o registro do aluno");
		header("Location:../exibir/listar.php");
} else {
		// echo "Existem est�gios relacionados com este aluno. <br>Exclua primeiro os est�gios para logo poder excluir o aluno";
		header("Location:ver_cancela.php?id_aluno=$id_aluno&erro=0");
}

exit;

?>
