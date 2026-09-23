<?php

include_once("../../setup.php");

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : NULL;

if(empty($ordem))
	$ordem = "instituicao";
else
  	$indice = $ordem;

$smarty = new Smarty_estagio;

$sql_turma = "select max(periodo) as turma from estagiarios";
$resultado_turma = $db->Execute($sql_turma);
if ($resultado_turma === false) die ("Não foi possivel consultar a tabela turma_estagio");
$turma = $resultado_turma->fields['turma'];

$sql  = "select e.id, e.instituicao, e.convenio, e.area as id_area, e.beneficios ";
$sql .= " , a.area ";
$sql .= " from instituicoes as e ";
$sql .= " left join areas as a on e.area = a.id ";
$sql .= " left outer join estagiarios t on e.id = t.instituicao_id ";
$sql .= " where t.periodo = '$turma' ";
$sql .=	" group by e.instituicao, e.area, beneficios, e.id ";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado == false) die ("Não foi possível consultar a tabela instituicoes");

$i = 0;
while (!$resultado->EOF) {
  	$instituicao_id = $resultado->fields['instituicao_id'];
  	$instituicao    = $resultado->fields['instituicao'];
  	$area_id        = $resultado->fields['area_id'];
  	$area_nome      = $resultado->fields['area'];
	$beneficio      = $resultado->fields['beneficios'];
  	$convenio       = $resultado->fields['convenio'];

  	// echo $convenio . "<br>";
  	$resultado->MoveNext();

    // Pego a quantidade de supervisores por instituicao
    $sql_supervisores  = "select s.id as num_supervisor ";
    $sql_supervisores .= " from inst_super as i ";
    $sql_supervisores .= " , supervisores as s ";
    $sql_supervisores .= " where i.supervisor_id=s.id and i.instituicao_id=$instituicao_id";
	// echo $sql_supervisores . "<br>";
    $res_supervisores = $db->Execute($sql_supervisores);
    if ($res_supervisores === false) die ("Não foi possível consultar as tabelas supervisores/inst_super");
    $q_supervisores = $res_supervisores->RecordCount();
	// echo $q_supervisores . "<br>";

    // Pego a turma das instituicoes
    $sql_turma = "select periodo as turma from estagiarios where instituicao_id=$instituicao_id order by periodo";
	// echo $sql_turma . "<br>";
    $resultado_turma = $db->Execute($sql_turma);
    if ($resultado_turma === false) die ("Não foi possível consultar a tabela turma_estagio");
    $q_turma = $resultado_turma->RecordCount();
    // echo $q_turma . "<br>";
	if ($q_turma != 0) {
    	while(!$resultado_turma->EOF) {
			$turma = $resultado_turma->fields['turma'];
    		// echo $turma . "<br>";
			$resultado_turma->MoveNext();
    	}
	} else {
		$turma = "";
	}

  	$matriz[$i][$ordem] = $$indice;
  	$matriz[$i]['instituicao_id'] = $instituicao_id;
  	$matriz[$i]['instituicao']    = $instituicao;
  	$matriz[$i]['supervisores']   = $q_supervisores;
  	$matriz[$i]['turma']          = $turma;
  	$matriz[$i]['area_id']        = $area_id;
  	$matriz[$i]['area']           = $area_nome;
  	$matriz[$i]['convenio']       = $convenio;
  	$matriz[$i]['beneficio']      = $beneficio;
  	$i++;
}

reset($matriz);
sort($matriz);

// $smarty->assign("instituicoes",$tabela);
$smarty->assign("instituicoes",$matriz);
$smarty->display("instituicoes.tpl");

exit;

?>
