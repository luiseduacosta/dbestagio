<?php

// Script de manutencao: exige sessao autenticada e perfil admin. A permissao e
// conferida aqui, nunca apenas na tela (mesmo criterio de exigirAdmin()).
// autentica.inc ja inclui setup.php, entao $db continua disponivel.
require_once(__DIR__ . "/../autentica.inc");
if (empty($isAdmin)) {
	die("Acesso restrito: apenas administradores podem executar esta manutencao.");
}

$sql_estagiarios = "select id, registro, nivel, periodo from estagiarios group by registro order by periodo, registro";
$estagiarios = $db->Execute($sql_estagiarios);
while (!$estagiarios->EOF) {
	$registro = $estagiarios->fields['registro'];
	$id = $estagiarios->fields['id'];
	$nivel = $estagiarios->fields['nivel'];
	$periodo = $estagiarios->fields['periodo'];
	// echo $registro . " ";
	$sql_alunos = "select id, nome from alunos where registro = $registro";
	$alunos = $db->Execute($sql_alunos);
	$quantidade = $alunos->RecordCount();
	// Aluno existe se quantidade e diferente de 0
	if ($quantidade >= 1) {
		$nome = $alunos->fields['nome'];
	} elseif ($quantidade === 0) {
		echo "<p style='background-color:yellow'>" . $id . " " . $quantidade . " " . $registro . " "  . "Estagiário não cadastrado como aluno "  . $nivel . " " . $periodo . "</p>";
	}
	$estagiarios->MoveNext();
}

?>