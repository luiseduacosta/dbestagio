<?php

/* 
* Busca alunos novos sem inscricoes na tabela mural_inscricoes
*/

include_once("../../db.inc");

$sql = "select id, registro from alunosNovos order by id";
$res = $db->Execute($sql);

while (!$res->EOF) {
	$id = $res->fields['id'];
	$registro = $res->fields['registro'];

	$sql_inscricao = "select id, id_aluno from mural_inscricao where id_aluno = '$registro'";
	// echo $sql_inscricao . "<br>";
	$res_inscricao = $db->Execute($sql_inscricao);
	$quantidade = $res_inscricao->RecordCount();
	if ($quantidade == 0) {
		echo "$registro -> Registro alunoNovos sem inscricoes <br>";
	}
	$res->MoveNext();
}

?>