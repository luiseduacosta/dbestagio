<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include_once("../../autentica.inc");

$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'super_email';
$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : NULL;
if (!$periodo) $periodo = PERIODO_ATUAL;

// Consulto a view: supervisor_aluno
$sql = "select * from supervisor_aluno where periodo = '$periodo' order by $ordem";
// echo $sql . "<br>";
$res_sql = $db->Execute($sql);

if ($res_sql === false) die ("Não foi possível consultar a tabela supervisor_aluno");

$i = 0;
while (!$res_sql->EOF) {
    
    $alunosupervisor[$i]['aluno_id'] = $res_sql->fields['aluno_id'];
    $alunosupervisor[$i]['aluno'] = $res_sql->fields['aluno'];
    $alunosupervisor[$i]['registro'] = $res_sql->fields['registro'];
    $alunosupervisor[$i]['celular'] = $res_sql->fields['celular'];
    $alunosupervisor[$i]['telefone'] = $res_sql->fields['telefone'];
    $alunosupervisor[$i]['email'] = $res_sql->fields['email'];
    $alunosupervisor[$i]['estagiario_id'] = $res_sql->fields['estagiario_id'];
    $alunosupervisor[$i]['periodo'] = $res_sql->fields['periodo'];
    $alunosupervisor[$i]['supervisor_id'] = $res_sql->fields['supervisor_id']; 
    $alunosupervisor[$i]['supervisor'] = $res_sql->fields['supervisor']; 
    $alunosupervisor[$i]['cress'] = $res_sql->fields['cress'];
    $alunosupervisor[$i]['super_email'] = $res_sql->fields['super_email'];
    $alunosupervisor[$i]['super_celular'] = $res_sql->fields['super_celular'];
    $alunosupervisor[$i]['super_telefone'] = $res_sql->fields['super_telefone'];
    $alunosupervisor[$i]['instituicao_id'] = $res_sql->fields['instituicao_id'];
    $alunosupervisor[$i]['instituicao'] = $res_sql->fields['instituicao'];
    
    $i++;
    
    $res_sql->MoveNext();
    
}

// Pego a informacao sobre as turma de alunos
$sqlturma = "select id, periodo from estagiarios group by periodo";
// echo $sqlturma . "<br>";
$res_turma = $db->Execute($sqlturma);
if ($res_turma === false) die ("Não foi possível consultar a tabela estagiarios");
while (!$res_turma->EOF) {
	$periodos[] = $res_turma->fields['periodo'];
	$res_turma->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("periodos",$periodos);
$smarty->assign("periodo",$periodo);
$smarty->assign("alunosupervisor",$alunosupervisor);
$smarty->display("supervisores-aluno_supervisor.tpl");

exit;

?>
