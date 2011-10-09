<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("../../setup.php");

// Verifico se o usuario esta logado
if (isset($_REQUEST['usuario_senha'])) {
    $usuario = $_REQUEST['usuario_senha'];
    if ($usuario) 
	$logado = 1;
}

$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'super_email';
$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : NULL;
if (!$periodo) $periodo = PERIODO_ATUAL;

$sql = "select * from supervisor_aluno where periodo = '$periodo' order by $ordem";
// echo $sql . "<br>";
$res_sql = $db->Execute($sql);

if ($res_sql === false) die ("Não foi possível consultar a tabela supervisor_aluno");

$i = 0;
while (!$res_sql->EOF) {
    
    $alunosupervisor[$i]['id_aluno'] = $res_sql->fields['id_aluno'];
    $alunosupervisor[$i]['aluno'] = $res_sql->fields['aluno'];
    $alunosupervisor[$i]['registro'] = $res_sql->fields['registro'];
    $alunosupervisor[$i]['celular'] = $res_sql->fields['celular'];
    $alunosupervisor[$i]['telefone'] = $res_sql->fields['telefone'];
    $alunosupervisor[$i]['email'] = $res_sql->fields['email'];
    $alunosupervisor[$i]['id_estagiario'] = $res_sql->fields['id_estagiario'];
    $alunosupervisor[$i]['periodo'] = $res_sql->fields['periodo'];
    $alunosupervisor[$i]['id_supervisor'] = $res_sql->fields['id_supervisor']; 
    $alunosupervisor[$i]['supervisor'] = $res_sql->fields['supervisor']; 
    $alunosupervisor[$i]['cress'] = $res_sql->fields['cress'];
    $alunosupervisor[$i]['super_email'] = $res_sql->fields['super_email'];
    $alunosupervisor[$i]['super_celular'] = $res_sql->fields['super_celular'];
    $alunosupervisor[$i]['super_telefone'] = $res_sql->fields['super_telefone'];
    $alunosupervisor[$i]['id_instituicao'] = $res_sql->fields['id_instituicao'];
    $alunosupervisor[$i]['instituicao'] = $res_sql->fields['instituicao'];
    
    $i++;
    
    // echo $alunosupervisor['aluno'] . "<br>";
    
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
$smarty->assign("logado",$logado);
$smarty->assign("periodos",$periodos);
$smarty->assign("periodo",$periodo);
$smarty->assign("alunosupervisor",$alunosupervisor);
$smarty->display("supervisores-aluno_supervisor.tpl");

$db->Close();

exit;

?>
