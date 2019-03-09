<?php

include_once("../setup.php");

$sql = "select id, id_aluno from mural_inscricao order by id_aluno";
// echo $sql . "<br>";

$res_sql = $db->Execute($sql);
if ($res_sql === false) die("Não foi possível a consulta da tabela");
while (!$res_sql->EOF) {

	$id = $res_sql->fields['id'];
	$registro = $res_sql->fields['id_aluno'];
	$res_sql->MoveNext();
	// echo $id . " " . $registro . "<br>";

	$sql_alunonovo = "select id, registro from alunosNovos where registro = $registro";
	// echo $sql_alunonovo . "<br>";
	$res_alunonovo = $db->Execute($sql_alunonovo);
	$alunonovo = $res_alunonovo->fields['registro'];
	$id_alunonovo = $res_alunonovo->fields['id'];
	// echo $alunonovo . "<br>";
	if ($alunonovo) {

		$sql_updade = "update mural_inscricao set aluno_id = $id_alunonovo where id_aluno = $registro";
		// echo "Novo: " . $sql_updade . "<br>";

	} else {

		$sql_aluno = "select id, registro from alunos where registro = $registro";
		// echo $sql_alunonovo . "<br>";
		$res_aluno = $db->Execute($sql_aluno);
		$aluno = $res_aluno->fields['registro'];
		$id_aluno = $res_aluno->fields['id'];		
		// echo "Aluno estagiario: " . $aluno . "<br>";

		$sql_updade_estagiario = "update mural_inscricao set aluno_id = $id_aluno where id_aluno = $registro";
		echo "Velho: " . $sql_updade_estagiario . "<br>";
		
	}
}

?>