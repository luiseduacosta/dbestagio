<?php

if($debug == 1)
	echo $_SERVER['PHP_SELF'];

include_once("../../autentica.inc");

include_once("../../db.inc");
include_once("../../setup.php");

$origem = $_REQUEST['origem'];
if(empty($origem )) {
	$origem = $_SERVER['HTTP_REFERER']; // Para poder retornar desde onde foi chamado
}

$id_estagiarios = $_REQUEST['id_estagiarios'];
$id_aluno = $_REQUEST['id_aluno'];

// Aluno
/* Seria melhor capturar toda a informacao do aluno */
$sql_aluno = "select nome, registro from alunos where id='$id_aluno'";
$resultado_aluno = $db->Execute($sql_aluno);
if($resultado_aluno === false) die ("Nao foi possivel consultar a tabela alunos");
while(!$resultado_aluno->EOF) {
	$nome_aluno = $resultado_aluno->fields['nome'];
	$registro   = $resultado_aluno->fields['registro'];
	$resultado_aluno->MoveNext();
}

// Estagiarios
$sql_estagiarios = "select * from estagiarios where id=$id_estagiarios";
// echo $sql_estagiarios . "<br>";
$estagiarios = $db->Execute($sql_estagiarios);
if($estagiarios === false) die ("Nao foi possivel consultar a tabela estagiarios");
$i = 0;
while(!$estagiarios->EOF) {
	$periodo        = $estagiarios->fields["periodo"];
	$tc	            = $estagiarios->fields["tc"];
	$turno          = $estagiarios->fields["turno"];
	$nivel          = $estagiarios->fields["nivel"];
	$id_instituicao = $estagiarios->fields["id_instituicao"];
	$id_supervisor  = $estagiarios->fields["id_supervisor"];
	$id_professor   = $estagiarios->fields["id_professor"];
	$id_area        = $estagiarios->fields["id_area"];
	$nota           = $estagiarios->fields["nota"];
	$ch             = $estagiarios->fields["ch"];

	// Nome da Instituicao
	if(!empty($id_instituicao)) {
		$sql_instituicao = "select id, instituicao from estagio where id=$id_instituicao";
		$resultado_instituicao = $db->Execute($sql_instituicao);
		if($resultado_instituicao === false) die ("Nao foi possivel consultar a tabela estagio");
		while(!$resultado_instituicao->EOF) {
			$instituicao = $resultado_instituicao->fields["instituicao"];
			$resultado_instituicao->MoveNext();
		}
	} else {
		$id_instituicao = 0;
		$instituicao = "Sem dados";
	}

	// Nome do Supervisor
	if(!empty($id_supervisor)) {
		$sql_nome_supervisor = "select nome from supervisores where id=$id_supervisor";
		$resultado_nome_supervisor = $db->Execute($sql_nome_supervisor);
		if($resultado_nome_supervisor === false) die ("Nao foi possivel consultar a tabela supervisores");
		while(!$resultado_nome_supervisor->EOF)	{
			$supervisor = $resultado_nome_supervisor->fields["nome"];
			$resultado_nome_supervisor->MoveNext();
		}
	} else {
		$id_supervisor = 0;
		$supervisor = "Sem dados";
	}

	// Nome do Professor
	if(!empty($id_professor)) {
		$sql_nome_professor = "select nome from professores where id=$id_professor";
		$resultado_nome_professor = $db->Execute($sql_nome_professor);
		if($resultado_nome_professor === false) die ("Nao foi possivel consultar a tabela professores");
		while(!$resultado_nome_professor->EOF) {
			$professor = $resultado_nome_professor->fields["nome"];
			$resultado_nome_professor->MoveNext();
		}
	} else {
		$id_professor = 0;
		$professor = "Sem dados";
	}

	// Nome do area
	if(!empty($id_area)) {
		$sql_nome_area = "select area from areas_estagio where id=$id_area";
		$resultado_nome_area = $db->Execute($sql_nome_area);
		if($resultado_nome_area === false) die ("Nao foi possivel consultar a tabela areas_estagio");
		while(!$resultado_nome_area->EOF) {
			$nome_area = $resultado_nome_area->fields["area"];
			$resultado_nome_area->MoveNext();
		}
	} else {
		$id_area = 0;
		$area = "Sem dados";
	}

	$estagiarios->MoveNext();
	$i++;
}

// Capturo a informacao sobre as instituicoes
$sql = "select id, instituicao from estagio order by instituicao";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Nao foi possivel consultar a tabela estagio");
$i = 0;
$instituicoes[$i]['id_instituicao'] = "0";
$instituicoes[$i]['instituicao'] = "Sem dados";
$i++;
while(!$resultado->EOF) {
	$instituicoes[$i]['id_instituicao'] = $resultado->fields['id'];
	$instituicoes[$i]['instituicao']    = $resultado->fields['instituicao'];
	$resultado->MoveNext();
	$i++;
}

// Capturo a informacao sobre os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$resultado_supervisores = $db->Execute($sql_supervisores);
if($resultado_supervisores === false) die ("Nao foi possivel consultar a tabela supervisores");
$i = 0;
$supervisores[$i]['id_supervisor'] = "0";
$supervisores[$i]['supervisor'] = "Sem dados";
$i++;
while(!$resultado_supervisores->EOF) {
	$supervisores[$i]['id_supervisor'] = $resultado_supervisores->fields['id'];
	$supervisores[$i]['supervisor']    = $resultado_supervisores->fields['nome'];
	$resultado_supervisores->MoveNext();
	$i++;
}

// Capturo a informacao sobre os professores
$sql_professores = "select id, nome from professores order by nome";
$resultado_professores = $db->Execute($sql_professores);
if($resultado_professores === false) die ("Nao foi possivel consultar a tabela professores");
$i = 0;
$professores[$i]['id_professor'] = "0";
$professores[$i]['professor'] = "Sem dados";
$i++;
while(!$resultado_professores->EOF) {
	$professores[$i]['id_professor'] = $resultado_professores->fields['id'];
	$professores[$i]['professor']    = $resultado_professores->fields['nome'];
	$resultado_professores->MoveNext();
	$i++;
}

// Capturo a informacao sobre as areas
$sql_areas = "select id, area from areas_estagio order by area";
$resultado_areas = $db->Execute($sql_areas);
if($resultado_areas === false) die ("Nao foi possivel consultar a tabela areas_estagio");
$i = 0;
$areas[$i]['id_area'] = "0";
$areas[$i]['area'] = "Sem dados";
$i++;
while(!$resultado_areas->EOF) {
	$areas[$i]['id_area'] = $resultado_areas->fields['id'];
	$areas[$i]['area']    = $resultado_areas->fields['area'];
	$resultado_areas->MoveNext();
	$i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("atualizar_estagio",1);
$smarty->assign("origem",$origem);
$smarty->assign("id_aluno",$id_aluno);
$smarty->assign("nome_aluno",$nome_aluno);
$smarty->assign("registro",$registro);
$smarty->assign("periodo",$periodo);
$smarty->assign("tc",$tc);
$smarty->assign("turno",$turno);
$smarty->assign("nivel",$nivel);
$smarty->assign("id_estagiarios",$id_estagiarios);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("nome_instituicao",$instituicao);
$smarty->assign("id_supervisor",$id_supervisor);
$smarty->assign("nome_supervisor",$supervisor);
$smarty->assign("id_professor",$id_professor);
$smarty->assign("nome_professor",$professor);
$smarty->assign("id_area",$id_area);
$smarty->assign("nome_area",$nome_area);
$smarty->assign("nota",$nota);
$smarty->assign("ch",$ch);
$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("supervisores",$supervisores);
$smarty->assign("professores",$professores);
$smarty->assign("areas",$areas);

$smarty->display("alunos-atualizar_atualiza_estagio.tpl");

?>