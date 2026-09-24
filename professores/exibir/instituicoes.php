<?php

// include_once("../../db.inc");
include_once("../../setup.php");

$sql = "select id, nome from professores order by nome";
$resultado = $db->Execute($sql);
while (!$resultado->EOF) {
	
	$id = $resultado->fields['id'];
	$nome = $resultado->fields['nome'];
	
	$sql_instituicoes = "select instituicao from instituicoes inner join estagiarios on instituicoes.id = estagiarios.instituicao_id where estagiarios.professor_id = $id group by instituicao";
	// echo $sql_instituicoes . "<br>";
	$resultado_instituicoes = $db->Execute($sql_instituicoes);
	while (!$resultado_instituicoes->EOF) {
		$instituicao = $resultado_instituicoes->fields['instituicao'];
		/*
		echo "
		<table border='1'>
		<tr>
		<td>$nome</td><td>$instituicao</td>
		</tr>
		</table>
		";
		*/		
		$resultado_instituicoes->MoveNext();
	}
	
	$resultado->MoveNext();
}

function sel_professor($db) {
	
	$sql = "select id, nome from professores order by nome";
	$resultado = $db->Execute($sql);
	while (!$resultado->EOF) {
	
		$professores['id'][$i] = $resultado->fields['id'];
		$professores['nome'][$i] = $resultado->fields['nome'];
	
		$i++;
		
		$resultado->MoveNext();
	}
	return $professores;
}

function sel_instituicao($professores,$db) {

	$j = 0;
	for ($i=1;$i < sizeof($professores['nome']);$i++) {

		$id   = $professores['id'][$i];
		$nome = $professores['nome'][$i];

		$sql_instituicoes = "select instituicao from instituicoes inner join estagiarios on instituicoes.id = estagiarios.instituicao_id where estagiarios.professor_id = $id group by instituicao";
		// echo $sql_instituicoes . "<br>";
		
		$resultado_instituicoes = $db->Execute($sql_instituicoes);
		
		while (!$resultado_instituicoes->EOF) {
			$instituicao = $resultado_instituicoes->fields['instituicao'];

			$inst_professor[$j]['nome'] = $nome;
			$inst_professor[$j]['instituicao'] = $instituicao;

			$j++;
			
			$resultado_instituicoes->MoveNext();
		}
		
	}

	return $inst_professor;

}

$instituicoes = sel_instituicao(sel_professor($db),$db);

$smarty = new Smarty_estagio;
$smarty->assign("instituicoes",$instituicoes);
$smarty->display("professores_instituicoes.tpl");

?>