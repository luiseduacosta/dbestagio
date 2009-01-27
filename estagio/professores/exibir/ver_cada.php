<?php

// Conto a quantidade de registros
function contar($db) {
	$sql = "select id from professores where dataegresso = '0000-00-00' order by nome";
	$resultado = $db->Execute($sql);
	if ($resultado === false) die ("contar: Não foi possível consultar a tabela professores");
	$quantidade = $resultado->RecordCount();
	
	return $quantidade;
}

// Busco o lugar do professor na tabela
function lugar($id_professor,$db) {
	$sql_professor = "select id from professores where dataegresso = '0000-00-00' order by nome";
	$resultado_professor = $db->Execute($sql_professor);
	if ($resultado_professor === false) die ("lugar: Não foi possível consultar a tabela professores");
	$lugar = 0;
	while (!$resultado_professor->EOF)	{
		$num_professor = $resultado_professor->fields['id'];
		if ($num_professor === $id_professor) {
			$indice = $lugar;
		}
		$lugar++;
		$resultado_professor->MoveNext();
	}
	return $indice;
}

function ver_cada($indice,$db) {
	$sql = "select id, nome from professores where dataegresso = '0000-00-00' order by nome";
	$resultado = $db->SelectLimit($sql,1,$indice);
	if ($resultado === false) die ("ver_cada: Não foi possível consultar a tabela professores");	
	while (!$resultado->EOF) {
		$professor['id'] = $resultado->fields['id'];
		$professor['nome'] = $resultado->fields['nome'];

		$resultado->MoveNext();
	}
	return $professor;
}

// Busco as instituicoes com as quais o professor trabalha
function instituicao($id_professor,$db) {
	$sql_instituicao = "select estagio.id, estagio.instituicao from estagio inner join estagiarios on estagio.id = estagiarios.id_instituicao where estagiarios.id_professor = $id_professor group by instituicao";	
	// echo $sql_instituicao . "<br>";
	$resultado = $db->Execute($sql_instituicao);
	if ($resultado === false) die ("institucao: Não foi possível consultar as tabelas estagio e estagiarioes");
	$i = 0;
	while (!$resultado->EOF) {

		$instituicao[$i]['id_instituicao'] = $resultado->fields['id'];
		$instituicao[$i]['instituicao']    = $resultado->fields['instituicao'];

		$resultado->MoveNext();
		$i++;
		
	}
	return $instituicao;
}

// Alunos que estagiaram com o professor
function alunos($id_professor,$db,$ordem="nome") {
	$sql = "select alunos.id, alunos.registro, alunos.nome, estagiarios.periodo, estagiarios.id_instituicao, estagio.instituicao from alunos inner join estagiarios on alunos.id = estagiarios.id_aluno inner join estagio on estagiarios.id_instituicao = estagio.id where estagiarios.id_professor = $id_professor order by $ordem";
	// echo $sql . "<br>";
	$resultado = $db->Execute($sql);
	if ($resultado === false) die ("alunos: Não foi possível consultar as tabelas alunos, estagiarios e estagio");
	$i = 0;
	while (!$resultado->EOF) {
		$alunos[$i]['id_aluno'] = $resultado->fields['id'];
		$alunos[$i]['registro'] = $resultado->fields['registro'];
		$alunos[$i]['nome'] = $resultado->fields['nome'];
		$alunos[$i]['periodo'] = $resultado->fields['periodo'];
		$alunos[$i]['id_instituicao'] = $resultado->fields['id_instituicao'];
		$alunos[$i]['instituicao'] = $resultado->fields['instituicao'];
		
		$resultado->MoveNext();
		$i++;
			
	}
	return $alunos;
}

require_once("../../setup.php");

$id_professor = isset($_REQUEST['id_professor']) ? $_REQUEST['id_professor'] : NULL;
$ordem  = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : "nome";

$indice = $_REQUEST['indice'];
$submit = $_REQUEST['submit'];
$botao  = $_REQUEST['botao'];

// echo " Indice: " . $indice . "<br>";
// echo " Submit: " . $submit . "<br>";
// echo " Botao: " . $botao . "<br>";

$num_linhas = contar($db);
// echo $num_linhas . "<br>";

switch($botao)
{
    case "primeiro":
	$indice = 0;
	break;

    case "menos_1";
	$indice--;
	if($indice < 0)
	    $indice = $num_linhas - 1;
	break;

    case "menos_10":
	$indice = $indice - 10;
	if($indice < 0)
	    $indice = ($num_linhas-1) - abs($indice);
	break;

    case "mais_1":
	$indice++;
	if($indice > ($num_linhas-1))
	    $indice = 0;
	break;

    case "mais_10":
	$indice = $indice + 10;
	if($indice > ($num_linhas-1))
	    $indice = $indice - $num_linhas;
	break;

    case "ultimo":
	$indice = $num_linhas-1;
	break;
}

if (!empty($id_professor)) {
	$indice = lugar($id_professor,$db);
}

$professor = ver_cada($indice,$db);
$id_professor = $professor['id'];
$instituicoes = instituicao($id_professor,$db);
$alunos = alunos($id_professor,$db,$ordem);

$smarty = new Smarty_estagio;
$smarty->assign("professor",$professor);
$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("alunos",$alunos);
$smarty->assign("indice",$indice);
$smarty->display("professores_ver_cada.tpl");

?>