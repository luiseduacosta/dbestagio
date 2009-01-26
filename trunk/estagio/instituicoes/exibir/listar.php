<?php

include_once("../../db.inc");
include_once("../../setup.php");

$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : "instituicao";
$turma = isset($_GET['turma']) ? $_GET['turma'] : NULL;

$smarty = new Smarty_estagio;

// Calculo a quantidade de alunos total e/ou por periodo
$sql_alunos  = "select count(registro) as total_alunos from estagiarios";
if ($turma) $sql_alunos .= " where periodo='$turma' ";
$sql_alunos .= " group by registro";
// echo $sql_alunos . "<br>";
$res_alunos = $db->Execute($sql_alunos);
if ($res_alunos == false) die ("N�o foi poss�vel consultar a tabela estagiarios");
while (!$res_alunos->EOF) {
	$total_alunos = $res_alunos->fields['total_alunos'];
	$todos_alunos++;
	$res_alunos->MoveNext();
}
// echo "Total de alunos: ". $todos_alunos . " " . $total_alunos . "<br>";

// Calculo a quantidade de instituicoes total e/ou por periodo
$sql_instituicoes  = "select count(id_instituicao) as total_instituicao from estagiarios";
if ($turma) $sql_instituicoes .= " where periodo='$turma' ";
$sql_instituicoes .= " group by id_instituicao";
// echo $sql_instituicoes . "<br>";
$res_instituicoes = $db->Execute($sql_instituicoes);
if ($res_instituicoes == false) die ("N�o foi poss�vel consultar a tabela estagiarios");
while (!$res_instituicoes->EOF) {
	$total_instituicoes = $res_instituicoes->fields['total_instituicoes'];
	$todas_instituicoes++;
	$res_instituicoes->MoveNext();
}
// echo "Total de instituicoes: ". $todas_instituicoes . " " . $total_instituicoes . "<br>";

$sql  = "select e.id, e.instituicao, e.convenio, e.area as id_area, e.beneficio ";
$sql .= " , a.area ";
$sql .= " , t.id_supervisor ";
$sql .= " from estagio as e ";
$sql .= " left join areas_estagio as a on e.area = a.id ";
$sql .= " left outer join estagiarios t on e.id = t.id_instituicao ";
if ($turma) $sql .= " where t.periodo = '$turma' ";
$sql .=	" group by e.instituicao, e.area, beneficio, e.id ";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado == false) die ("N�o foi poss�vel consultar a tabela estagio");

$i = 0;
while(!$resultado->EOF) {
  	$id_instituicao = $resultado->fields['id'];
  	$instituicao    = $resultado->fields['instituicao'];
  	$id_area        = $resultado->fields['id_area'];
  	$area           = $resultado->fields['area'];
	$beneficio      = $resultado->fields['beneficio'];
  	$convenio       = $resultado->fields['convenio'];
	$id_supervisor	= $resultado->fields['id_supervisor'];
  	
  	$resultado->MoveNext();
	
	// Quantidade de supervisores por periodo e instituicao
	$sql_supervi  = "select id_supervisor from estagiarios where id_instituicao='$id_instituicao' ";
	if ($turma)$sql_supervi .= " and periodo = '$turma' ";
	$sql_supervi .= " group by id_supervisor";
	// echo $sql_supervi . "<br>";
	$res_supervi = $db->Execute($sql_supervi);
	$q_supervi = $res_supervi->RecordCount();
	// $supervisor_id = $res_supervi->fields['id_supervisor'];
	// echo $i . " " .  $supervisor_id . "<br>";
	$total_supervi = $total_supervi + $q_supervi;
	// echo " Super " . $id_supervisor . " quantidade: " . $q_supervi .  " acumulado: " . $total_supervi . "<br>";
	
    // Pego a ultima turma de cada instituicao
    $sql_turma = "select max(periodo) as turma from estagiarios where id_instituicao=$id_instituicao";
	// echo $sql_turma . "<br>";
    $resultado_turma = $db->Execute($sql_turma);
    if ($resultado_turma === false) die ("N�o foi poss�vel consultar a tabela turma_estagio");
    $q_turma = $resultado_turma->RecordCount();
    // echo $q_turma . "<br>";
	if ($q_turma != 0) { 
		$turma_estagiarios = $resultado_turma->fields['turma'];
	} else {
		$turma_estagiarios = "";
	}

	// Quantidade de alunos por periodos
	$sql_alunos = "select count(registro) as q_alunos from estagiarios where id_instituicao='$id_instituicao'";
	if ($turma) $sql_alunos .= " and periodo='$turma' ";
	$sql_alunos .= " group by registro ";
	// echo $sql_alunos . "<br>";
	$res_alunos = $db->Execute($sql_alunos);
	$total_periodos = 0;
	$j = 0;
	while (!$res_alunos->EOF){
		$q_alunos = $res_alunos->fields['q_alunos'];
		$total_periodos = $total_periodos + $q_alunos;
		// $todos_periodos = $todos_periodos + $total_periodos;
		$res_alunos->MoveNext();
		$j++;
	}
	$todos_periodos = $todos_periodos + $total_periodos;
	// echo "Todos periodos: " . $todos_periodos . "<br>";
	
	// Pego o mural da instituicao (por enquanto nao tem utilidade)
	$sql_mural = "select id, periodo from mural_estagio where id_estagio=$id_instituicao";
	// echo $sql_mural . "<br>";
	$resultado_mural = $db->Execute($sql_mural);
    if ($resultado_mural === false) die ("N�o foi poss�vel consultar a tabela mural_estagio");	

	while(!$resultado_mural->EOF) {
		$id_mural = $resultado_mural->fields['id'];
		$periodo_mural = $resultado_mural->fields['periodo'];
		$resultado_mural->MoveNext();
	}
	
	if(empty($ordem))
   	    $ordem = "instituicao"; 
  	else
    	$indice = $ordem;

  	$matriz[$i][$ordem] = $$indice;
  	$matriz[$i]['id_instituicao'] = $id_instituicao;
  	$matriz[$i]['instituicao']    = $instituicao;
  	$matriz[$i]['supervisores']   = $q_supervi;
  	$matriz[$i]['turma']          = $turma_estagiarios;
  	$matriz[$i]['alunos']         = $j;
	$matriz[$i]['periodos']       = $total_periodos;
  	$matriz[$i]['id_area']        = $id_area;
  	$matriz[$i]['area']           = $area;
  	$matriz[$i]['convenio']       = $convenio;
  	$matriz[$i]['beneficio']      = $beneficio;
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
if ($res_turma === false) die ("N�o foi possivel consultar a tabela estagiarios");
while (!$res_turma->EOF) {
	$periodos[] = $res_turma->fields['periodo'];
	$res_turma->MoveNext();
}

$smarty->assign("turma",$turma);
$smarty->assign("ordem",$ordem);
$smarty->assign("periodos",$periodos);
$smarty->assign("instituicoes",$matriz);
$smarty->assign("total_instituicoes",$todas_instituicoes);
$smarty->assign("total_supervisores",$total_supervi);
$smarty->assign("total_alunos",$todos_alunos);
$smarty->assign("total_periodos",$todos_periodos);
$smarty->display("instituicoes.tpl");

$db->Close();

exit;

?>