<?php
/*
 * Created on Jun 23, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once("../../autentica.inc");

$supervisor_id = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
$nome_supervisor = isset($_REQUEST['nome_supervisor']) ? htmlspecialchars(trim($_REQUEST['nome_supervisor']), ENT_QUOTES, 'UTF-8') : '';
$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : '';
// Whitelist de colunas permitidas para ORDER BY (proteção contra SQL injection).
$ordenacoes = array(
    'periodo, nome' => 'periodo, nome',
    'nome'          => 'nome',
    'periodo'       => 'periodo',
    'instituicao'   => 'instituicao',
    'nome_desc'     => 'nome DESC',
);
if (!isset($ordenacoes[$ordem])) {
    $ordem = 'periodo, nome';
}
$orderby = $ordenacoes[$ordem];

$sql  = "select estagiarios.id, estagiarios.aluno_id, estagiarios.registro, estagiarios.periodo, estagiarios.instituicao_id, ";
$sql .= " alunos.nome, alunos.email, ";
$sql .= " instituicoes.instituicao ";
$sql .= " from estagiarios ";
$sql .= " join alunos on estagiarios.registro = alunos.registro ";
$sql .= " join instituicoes on estagiarios.instituicao_id = instituicoes.id ";
$sql .= " where supervisor_id = ? order by $orderby";
$alunos = $db->Execute($sql, array($supervisor_id));
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
