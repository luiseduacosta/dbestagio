<?php

if ($debug == 1)
	echo $_SERVER['PHP_SELF'];

include_once("../../autentica.inc");

$origem = $_REQUEST['origem'];
if (empty($origem )) {
	$origem = $_SERVER['HTTP_REFERER']; // Para poder retornar desde onde foi chamado
}
// echo "Origem: " . $origem . "<br>";

$estagiario_id = isset($_REQUEST['estagiario_id']) ? $_REQUEST['estagiario_id'] : NULL;
$aluno_id = isset($_REQUEST['aluno_id']) ? $_REQUEST['aluno_id'] : NULL;

// Aluno
/* Seria melhor capturar toda a informacao do aluno */
$sql_aluno = "select nome, registro from alunos where id='$aluno_id'";
$resultado_aluno = $db->Execute($sql_aluno);
if ($resultado_aluno === false) die ("Nao foi possivel consultar a tabela alunos");
while (!$resultado_aluno->EOF) {
	$nome_aluno = $resultado_aluno->fields['nome'];
	$registro   = $resultado_aluno->fields['registro'];
	$resultado_aluno->MoveNext();
}

// Estagiarios
$sql_estagiarios = "select * from estagiarios where id=$estagiario_id";
// echo $sql_estagiarios . "<br>";
$estagiarios = $db->Execute($sql_estagiarios);
if ($estagiarios === false) die ("Nao foi possivel consultar a tabela estagiarios");
$i = 0;
while (!$estagiarios->EOF) {
	$periodo        = $estagiarios->fields["periodo"];
	$tc	        	= $estagiarios->fields["tc"];
	$nivel          = $estagiarios->fields["nivel"];
	$id_instituicao = $estagiarios->fields["instituicao_id"];
	$id_supervisor  = $estagiarios->fields["supervisor_id"];
	$id_professor   = $estagiarios->fields["professor_id"];
	$nota           = $estagiarios->fields["nota"];
	$ch             = $estagiarios->fields["ch"];

	// Nome da Instituicao
	if (!empty($instituicao_id)) {
		$sql_instituicao = "select id, instituicao from instituicoes where id=$instituicao_id";
		$res_instituicao = $db->Execute($sql_instituicao);
		if ($res_instituicao === false) die ("Nao foi possivel consultar a tabela instituicoes");
		while (!$res_instituicao->EOF) {
			$instituicao = $res_instituicao->fields["instituicao"];
			$res_instituicao->MoveNext();
		}
	} else {
		$instituicao_id = 0;
		$instituicao = "Sem dados";
	}

	// Nome do Supervisor
	if (!empty($supervisor_id)) {
		$sql_nome_supervisor = "select nome from supervisores where id=$supervisor_id";
		$resultado_nome_supervisor = $db->Execute($sql_nome_supervisor);
		if ($resultado_nome_supervisor === false) die ("Nao foi possivel consultar a tabela supervisores");
		while (!$resultado_nome_supervisor->EOF)	{
			$supervisor = $resultado_nome_supervisor->fields["nome"];
			$resultado_nome_supervisor->MoveNext();
		}
	} else {
		$supervisor_id = 0;
		$supervisor = "Sem dados";
	}

	// Nome do Professor
	if (!empty($professor_id)) {
		$sql_nome_professor = "select nome from professores where id=$professor_id";
		$resultado_nome_professor = $db->Execute($sql_nome_professor);
		if ($resultado_nome_professor === false) die ("Nao foi possivel consultar a tabela professores");
		while (!$resultado_nome_professor->EOF) {
			$professor = $resultado_nome_professor->fields["nome"];
			$resultado_nome_professor->MoveNext();
		}
	} else {
		$professor_id = 0;
		$professor = "Sem dados";
	}

	$estagiarios->MoveNext();
	$i++;
}

// Capturo a informacao sobre as instituicoes
$sql = "select id, instituicao from instituicoes order by instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela instituicoes");
$i = 0;
$instituicoes[$i]['instituicao_id'] = "0";
$instituicoes[$i]['instituicao'] = "Sem dados";
$i++;
while (!$resultado->EOF) {
	$instituicoes[$i]['instituicao_id'] = $resultado->fields['id'];
	$instituicoes[$i]['instituicao']    = $resultado->fields['instituicao'];
	$resultado->MoveNext();
	$i++;
}

// Capturo a informacao sobre os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$resultado_supervisores = $db->Execute($sql_supervisores);
if ($resultado_supervisores === false) die ("Nao foi possivel consultar a tabela supervisores");
$i = 0;
$supervisores[$i]['supervisor_id'] = "0";
$supervisores[$i]['supervisor'] = "Sem dados";
$i++;
while (!$resultado_supervisores->EOF) {
	$supervisores[$i]['supervisor_id'] = $resultado_supervisores->fields['id'];
	$supervisores[$i]['supervisor']    = $resultado_supervisores->fields['nome'];
	$resultado_supervisores->MoveNext();
	$i++;
}

// Capturo a informacao sobre os professores
$sql_professores = "select id, nome from professores order by nome";
$resultado_professores = $db->Execute($sql_professores);
if ($resultado_professores === false) die ("Nao foi possivel consultar a tabela professores");
$i = 0;
$professores[$i]['professor_id'] = "0";
$professores[$i]['professor'] = "Sem dados";
$i++;
while (!$resultado_professores->EOF) {
	$professores[$i]['professor_id'] = $resultado_professores->fields['id'];
	$professores[$i]['professor']    = $resultado_professores->fields['nome'];
	$resultado_professores->MoveNext();
	$i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("atualizar_estagio",1);
$smarty->assign("origem",$origem);
$smarty->assign("aluno_id",$aluno_id);
$smarty->assign("nome_aluno",$nome_aluno);
$smarty->assign("registro",$registro);
$smarty->assign("periodo",$periodo);
$smarty->assign("tc",$tc);
$smarty->assign("turno",$turno);
$smarty->assign("nivel",$nivel);
$smarty->assign("estagiario_id",$estagiario_id);
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("nome_instituicao",$instituicao);
$smarty->assign("supervisor_id",$supervisor_id);
$smarty->assign("nome_supervisor",$supervisor);
$smarty->assign("professor_id",$professor_id);
$smarty->assign("nome_professor",$professor);
$smarty->assign("nota",$nota);
$smarty->assign("ch",$ch);
$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("supervisores",$supervisores);
$smarty->assign("professores",$professores);

$smarty->display("alunos-atualizar_atualiza_estagio.tpl");

?>