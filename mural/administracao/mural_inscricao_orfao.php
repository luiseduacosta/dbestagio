<?php

/*
 * 
 * Busca inscricoes orfas para poder conservar a integridade da tabela inscricoes
 * 
 */

include_once("../../db.inc");

$sql_inscricao = "select id, registro from inscricoes order by registro";
echo $sql_inscricao . "<br>";
$res_inscricao = $db->Execute($sql_inscricao);

while (!$res_inscricao->EOF) {
	$id = $res_inscricao->fields['id'];
	$id_aluno = $res_inscricao->fields['registro'];

	$sql = "select id, registro from alunos where registro = $id_aluno";
	// echo $sql . "<br>";
	$res = $db->Execute($sql);
	$quantidade = $res->RecordCount();
	if ($quantidade == 0) {
		$sql_novos = "select id, registro from alunos where registro = $id_aluno";
		// echo $sql . "<br>";
		$res_novos = $db->Execute($sql_novos);
		$quantidade_novos = $res_novos->RecordCount();
		if ($quantidade_novos == 0) {			
			echo "<a href='mural-excluir_aluno.php?registro=$id_aluno'>$id_aluno</a>  Inscricoes sem alunos <br>";
		}
	}
	$res_inscricao->MoveNext();
}

?>