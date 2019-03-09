<?php

/*
 * 
 * Busca inscricoes orfas para poder conservar a integridade da tabela mural_inscricao
 * 
 */

include_once("../../db.inc");

$sql_inscricao = "select id, id_aluno from mural_inscricao order by id_aluno";
echo $sql_inscricao . "<br>";
$res_inscricao = $db->Execute($sql_inscricao);

while (!$res_inscricao->EOF) {
	$id = $res_inscricao->fields['id'];
	$id_aluno = $res_inscricao->fields['id_aluno'];

	$sql = "select id, registro from alunos where registro = $id_aluno";
	// echo $sql . "<br>";
	$res = $db->Execute($sql);
	$quantidade = $res->RecordCount();
	if ($quantidade == 0) {
		$sql_novos = "select id, registro from alunosNovos where registro = $id_aluno";
		// echo $sql . "<br>";
		$res_novos = $db->Execute($sql_novos);
		$quantidade_novos = $res_novos->RecordCount();
		if ($quantidade_novos == 0) {			
			echo "<a href='mural-excluir_aluno.php?id_aluno=$id_aluno'>$id_aluno</a>  Inscricoes sem alunos <br>";
		}
	}
	$res_inscricao->MoveNext();
}

?>