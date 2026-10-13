<?php
/*
 * Created on Jun 23, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - PHPeclipse - PHP - Templates
 */
require_once(__DIR__ . "/../../libphp/models.php");
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
$alunos = Estagiario::query($sql, array($supervisor_id));
if ($alunos === false) die ("Não foi possível consultar a tabela estagiarios, alunos");
$i = 0;
$estagiario = array();
foreach ($alunos as $item) {
	$estagiario[$i]['registro'] = $item->registro;
	$estagiario[$i]['aluno_id'] = (int)$item->aluno_id;
	$estagiario[$i]['nome'] = $item->nome;
	$estagiario[$i]['periodo'] = $item->periodo;
	$estagiario[$i]['email'] = $item->email;
	$estagiario[$i]['instituicao_id'] = (int)$item->instituicao_id;	
	$estagiario[$i]['instituicao'] = $item->instituicao;
	// echo $estagiario[$i]['registro'] . " " . $estagiario[$i]['nome'] . " " . $estagiario[$i]['email'] . " " . $estagiario[$i]['periodo'] . " ". $estagiario[$i]['instituicao'] . "<br>";
	$i++;
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