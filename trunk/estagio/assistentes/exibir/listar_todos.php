<?php

include_once("../../db.inc");
include_once("../../setup.php");

$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : "nome";
$turma = isset($_GET['turma']) ? $_GET['turma'] : NULL;
$id_instituicao = isset($_GET['id_instituicao']) ? $_GET['id_instituicao'] : NULL;

// echo $turma . "<br>";

$sql  = "select e.id as estagio_id, e.instituicao ";
$sql .= ", s.id as supervisor_id, s.cress, s.nome, s.email ";
$sql .= ", max(estagiarios.periodo)  as turma";
$sql .= " from supervisores as s ";
$sql .= " left outer join inst_super as i on s.id = i.id_supervisor ";
$sql .= " left outer join estagio as e on e.id = i.id_instituicao ";
$sql .= " left outer join estagiarios on s.id = estagiarios.id_supervisor ";

if (!empty($turma))
$sql .= " where estagiarios.periodo = '$turma' ";

if (!empty($id_instituicao))
$sql .= " where estagiarios.id_instituicao = '$id_instituicao' ";

$sql .= " group by s.id ";

// echo $sql . "<br>";

if(empty($ordem))
	$ordem = "nome";
else
	$indice = $ordem;

$resultado = $db->Execute($sql);
if($resultado == false) die ("Não foi possivel consultar as tabelas");
while(!$resultado->EOF) {

	$estagio_id_instituicao = $resultado->fields['estagio_id'];
	$id_supervisor          = $resultado->fields['supervisor_id'];
	$cress                  = $resultado->fields['cress'];
	$turma_periodo          = $resultado->fields['turma'];
	$nome_supervisor        = $resultado->fields['nome'];
	$email_supervisor       = $resultado->fields['email'];
	$estagio_instituicao    = $resultado->fields['instituicao'];

	$matriz[$i][$ordem]           = $$indice;
	$matriz[$i]['id_instituicao'] = $estagio_id_instituicao;
	$matriz[$i]['id_supervisor']  = $id_supervisor;
	$matriz[$i]['turma']  		  = $turma_periodo;
	$matriz[$i]['nome']           = $nome_supervisor;
	$matriz[$i]['instituicao']    = $estagio_instituicao;
	$matriz[$i]['email']    	  = $email_supervisor;
	$matriz[$i]['id_curso']       = $resultado->fields['id_curso'];

	// Calculo a quantidade de periodos que o supervisor trabalha com alunos
	$sql_periodos = "select count(distinct periodo) as q_periodos from estagiarios where id_supervisor=$id_supervisor";
	// echo $sql_periodos . "<br>";	
	$res_periodos = $db->Execute($sql_periodos);
	if($res_periodos === false) die ("Não foi possivel consultar a tabela estagiarios");
	$q_periodos = $res_periodos->fields['q_periodos'];	
	$matriz[$i]['q_periodos'] = $q_periodos;
	
	// Pego a informacao sobre curso de supervisores
	// Verifico que seja um numero
	if (ctype_digit($cress)) {
			$sqlcurso = "select id from curso_inscricao_supervisor where cress=$cress";
			// echo $sqlcurso . "<br />";
			$supervisores_curso = $db->Execute($sqlcurso);
			if($supervisores_curso === false) die ("Não foi possivel consultar a tabela curso_inscricao_supervisores");
			$matriz[$i]['id_curso'] = $supervisores_curso->fields['id'];
			$id_curso = $supervisores_curso->fields['id'];
			// echo $id_curso . "<br>";
	}

	$matriz[$i]['cress'] = $cress;
	$resultado->MoveNext();
	$i++;

}

reset($matriz);
sort($matriz);

/* Debugg
for($i=0;$i<sizeof($matriz);$i++) {
	print $matriz[$i]['id'] . " ";
	print $matriz[$i]['nome'] . " ";
	print $matriz[$i]['instituicao'] . "<br>";
}
*/

// Pego a informacao sobre as turma de alunos
$sqlturma = "select id, periodo from estagiarios group by periodo";
// echo $sqlturma . "<br>";
$res_turma = $db->Execute($sqlturma);
if($res_turma === false) die ("Não foi possivel consultar a tabela estagiarios");
while (!$res_turma->EOF) {
	$periodos[] = $res_turma->fields['periodo'];
	$res_turma->MoveNext();
}

// echo var_dump($periodos);

$smarty = new Smarty_estagio;

$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("turma",$turma);
$smarty->assign("ordem",$ordem);
$smarty->assign("periodos",$periodos);
$smarty->assign("supervisores",$matriz);
$smarty->display("supervisores.tpl");

if (empty($turma)) {
	include("supervisores.php");	
}

$db->Close();

exit;

?>