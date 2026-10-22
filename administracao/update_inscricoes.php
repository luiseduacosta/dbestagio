<?php

// Script de manutencao: exige sessao autenticada e perfil admin. A permissao e
// conferida aqui, nunca apenas na tela (mesmo criterio de exigirAdmin()).
// autentica.inc ja inclui setup.php, entao $db continua disponivel.
require_once(__DIR__ . "/../autentica.inc");
if (empty($isAdmin)) {
	die("Acesso restrito: apenas administradores podem executar esta manutencao.");
}

$sql = "";
$sql = "select id, registro from inscricoes order by registro";
// echo $sql . "<br>";
$res_sql = $db->Execute($sql);
if ($res_sql === false) die("Não foi possível a consulta da tabela");

while (!$res_sql->EOF) {
	$aluno_id = $res_sql->fields['id'];
	$registro = $res_sql->fields['registro'];

	if ($registro) {

		$aluno_sql = "";
		$aluno_sql = "select id from alunos where registro = $registro";
		$res_aluno = $db->Execute($aluno_sql);
		if ($res_aluno === false) die("Não foi possível a consulta do aluno");
		$aluno_id = $res_aluno->fields['id'];
		$sql_updade = "update inscricoes set aluno_id = $aluno_id where registro = $registro";
		$res_updade = $db->Execute($sql_updade);
		if ($res_updade === false) die("Não foi possível atualizar a inscricao");
	}
	$res_sql->MoveNext();
}

?>