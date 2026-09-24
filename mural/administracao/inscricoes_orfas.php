<?php

/* 
* Busca alunos novos sem inscricoes na tabela inscricoes
*/

include_once("../../db.inc");

$sql = "select id, registro from alunos order by id";
$res = $db->Execute($sql);

while (!$res->EOF) {
	$id = $res->fields['id'];
	$registro = $res->fields['registro'];

	$sql_inscricao = "select id, registro from inscricoes where registro = '$registro'";
	// echo $sql_inscricao . "<br>";
	$res_inscricao = $db->Execute($sql_inscricao);
	$quantidade = $res_inscricao->RecordCount();
	if ($quantidade == 0) {
		echo "$registro -> Registro aluno sem inscricoes <br>";
	}
	$res->MoveNext();
}

?>