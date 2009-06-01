<?php

include_once("../../autentica.inc");

include_once("../../db.inc");
include_once("../../setup.php");

$id_aluno = $_REQUEST['id_aluno'];

$sql_estagiario = "select * from estagiarios where id_aluno=$id_aluno";
// echo $sql_ex_estagiario . "<br>";
$resultado_sql_estagiario = $db->Execute($sql_estagiario);
if($resultado_sql_estagiario === false) die ("Não foi possível consultar a tabela estagiarios");
$quantidade = $resultado_sql_estagiario->RecordCount();

if($quantidade === 0)
{
		$sql_cancela_aluno = "delete from alunos where id=$id_aluno";
		// echo $sql_cancela_aluno . "<br>";
		$resultado_cancela_aluno = $db->Execute($sql_cancela_aluno);
		if($resultado_cancela_aluno === false) die ("Não foi possível cancelar o registro do aluno");
		header("Location:../exibir/listar.php");
} else {
		// echo "Existem estágios relacionados com este aluno. <br>Exclua primeiro os estágios para logo poder excluir o aluno";
		header("Location:ver_cancela.php?id_aluno=$id_aluno&erro=0");
}

exit;

?>
