<?php

include_once("../../db.inc");
include_once("../../setup.php");

$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : "instituicao";
$turma = isset($_GET['turma']) ? $_GET['turma'] : NULL;

$smarty = new Smarty_estagio;

$sql  = "select e.id, e.instituicao, e.convenio, e.area as id_area, e.beneficio ";
$sql .= " , a.area ";
$sql .= " from estagio as e ";
$sql .= " left join areas_estagio as a on e.area = a.id ";
$sql .= " left outer join estagiarios t on e.id = t.id_instituicao ";
if ($turma) $sql .= " where t.periodo = '$turma' ";
$sql .=	" group by e.instituicao, e.area, beneficio, e.id ";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado == false) die ("Não foi possível consultar a tabela estagio");

$i = 0;
while(!$resultado->EOF) {
  	$id_instituicao = $resultado->fields['id'];
  	$instituicao    = $resultado->fields['instituicao'];
  	$id_area        = $resultado->fields['id_area'];
  	$area           = $resultado->fields['area'];
	$beneficio      = $resultado->fields['beneficio'];
  	$convenio       = $resultado->fields['convenio'];
  	
  	// echo $convenio . "<br>";
  	$resultado->MoveNext();

    // Pego a quantidade de supervisores por instituicao
    $sql_supervisores  = "select s.id as num_supervisor ";
    $sql_supervisores .= " from inst_super as i ";
    $sql_supervisores .= " , supervisores as s ";
    $sql_supervisores .= " where i.id_supervisor=s.id and i.id_instituicao=$id_instituicao";
	// echo $sql_supervisores . "<br>";
    $res_supervisores = $db->Execute($sql_supervisores);
    if($res_supervisores === false) die ("Não foi possível consultar as tabelas supervisores/inst_super");
    $q_supervisores = $res_supervisores->RecordCount();
	// echo $q_supervisores . "<br>";

    // Pego a turma das instituicoes
    $sql_turma = "select max(periodo) as turma from estagiarios where id_instituicao=$id_instituicao";
	// echo $sql_turma . "<br>";
    $resultado_turma = $db->Execute($sql_turma);
    if($resultado_turma === false) die ("Não foi possível consultar a tabela turma_estagio");
    $q_turma = $resultado_turma->RecordCount();
    // echo $q_turma . "<br>";
	if ($q_turma != 0) { 
		$turma_estagiarios = $resultado_turma->fields['turma'];
	} else {
		$turma_estagiarios = "";
	}

	// Alunos no periodo
	if ($turma) {
		$sql_alunos = "select count(*) as alunos from estagiarios where id_instituicao='$id_instituicao' and periodo='$turma'";
		// echo $sql_alunos . "<br>";
		$res_alunos = $db->Execute($sql_alunos);
		$q_alunos = $res_alunos->fields['alunos'];
		// echo $q_alunos . "<br>";
	}
	
	// Pego o mural da instituicao (por enquanto nao tem utilidade)
	$sql_mural = "select id, periodo from mural_estagio where id_estagio=$id_instituicao";
	// echo $sql_mural . "<br>";
	$resultado_mural = $db->Execute($sql_mural);
    if($resultado_mural === false) die ("Não foi possível consultar a tabela mural_estagio");	

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
  	$matriz[$i]['supervisores']   = $q_supervisores;
  	$matriz[$i]['turma']          = $turma_estagiarios;
  	$matriz[$i]['alunos']         = $q_alunos;
  	$matriz[$i]['id_area']        = $id_area;
  	$matriz[$i]['area']           = $area;
  	$matriz[$i]['convenio']       = $convenio;
  	$matriz[$i]['beneficio']      = $beneficio;
  	$i++;
}

reset($matriz);
sort($matriz);
// array_multisort($matriz["supervisores"]);

/* Debugg 
for($i=0;$i<sizeof($matriz);$i++)
{

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

$smarty->assign("turma",$turma);
$smarty->assign("ordem",$ordem);
$smarty->assign("periodos",$periodos);
$smarty->assign("instituicoes",$matriz);
$smarty->display("instituicoes.tpl");

$db->Close();

exit;

?>