<?php
/*
 * Created on Jun 23, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once("../../autentica.inc");

$supervisor_id = $_REQUEST['supervisor_id'];
$nome_supervisor = $_REQUEST['nome_supervisor'];
$ordem = $_REQUEST['ordem'];
if (empty($ordem))
	$ordem = " periodo, nome";

$sql  = "select estagiarios.id, estagiarios.aluno_id, estagiarios.registro, estagiarios.periodo, estagiarios.instituicao_id, ";
$sql .= " alunos.nome, alunos.email, ";
$sql .= " instituicoes.instituicao ";
$sql .= " from estagiarios ";
$sql .= " join alunos on estagiarios.registro = alunos.registro ";
$sql .= " join instituicoes on estagiarios.instituicao_id = instituicoes.id ";
$sql .= " where supervisor_id=$supervisor_id order by $ordem";
// echo $sql . "<br>";
$alunos = $db->Execute($sql);
if ($alunos === false) die ("Não foi possível consultar a tabela estagiarios, alunos");
$i = 0;
while (!$alunos->EOF) {
	$estagiario[$i]['registro'] = $alunos->fields['registro'];
	$estagiario[$i]['aluno_id'] = $alunos->fields['aluno_id'];
	$estagiario[$i]['nome'] = $alunos->fields['nome'];
	$estagiario[$i]['periodo'] = $alunos->fields['periodo'];
	$estagiario[$i]['email'] = $alunos->fields['email'];
	$estagiario[$i]['instituicao_id'] = $alunos->fields['instituicao_id'];	
	$estagiario[$i]['instituicao'] = $alunos->fields['instituicao'];
	// echo $estagiario[$i]['registro'] . " " . $estagiario[$i]['nome'] . " " . $estagiario[$i]['email'] . " " . $estagiario[$i]['periodo'] . " ". $estagiario[$i]['instituicao'] . "<br>";
	$i++;
	$alunos->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("logado",$logado);
$smarty->assign("supervisor_id",$supervisor_id);
$smarty->assign("id_supervisor",$supervisor_id);
$smarty->assign("nome_supervisor",$nome_supervisor);
$smarty->assign("estagiario",$estagiario);
$smarty->display('alunos_supervisor.tpl');

exit;

?>
