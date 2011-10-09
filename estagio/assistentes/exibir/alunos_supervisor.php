<?php
/*
 * Created on Jun 23, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../../setup.php");

$id_supervisor = $_REQUEST['id_supervisor'];
$nome_supervisor = $_REQUEST['nome_supervisor'];
$ordem = $_REQUEST['ordem'];
if (empty($ordem))
	$ordem = " periodo, nome";

$sql  = "select estagiarios.id, estagiarios.id_aluno, estagiarios.registro, estagiarios.periodo, estagiarios.id_instituicao, ";
$sql .= " alunos.nome, alunos.email, ";
$sql .= " estagio.instituicao ";
$sql .= " from estagiarios ";
$sql .= " join alunos on estagiarios.registro = alunos.registro ";
$sql .= " join estagio on estagiarios.id_instituicao = estagio.id ";
$sql .= " where id_supervisor=$id_supervisor order by $ordem";
// echo $sql . "<br>";
$alunos = $db->Execute($sql);
if ($alunos === false) die ("Não foi possível consultar a tabela estagiarios, alunos");
$i = 0;
while (!$alunos->EOF) {
	$estagiario[$i]['registro'] = $alunos->fields['registro'];
	$estagiario[$i]['id_aluno'] = $alunos->fields['id_aluno'];
	$estagiario[$i]['nome'] = $alunos->fields['nome'];
	$estagiario[$i]['periodo'] = $alunos->fields['periodo'];
	$estagiario[$i]['email'] = $alunos->fields['email'];
	$estagiario[$i]['id_instituicao'] = $alunos->fields['id_instituicao'];	
	$estagiario[$i]['instituicao'] = $alunos->fields['instituicao'];
	// echo $estagiario[$i]['registro'] . " " . $estagiario[$i]['nome'] . " " . $estagiario[$i]['email'] . " " . $estagiario[$i]['periodo'] . " ". $estagiario[$i]['instituicao'] . "<br>";
	$i++;
	$alunos->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("id_supervisor",$id_supervisor);
$smarty->assign("nome_supervisor",$nome_supervisor);
$smarty->assign("estagiario",$estagiario);
$smarty->display('alunos_supervisor.tpl');

?>
