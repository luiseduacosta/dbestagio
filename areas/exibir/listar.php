<?php

include_once("../../setup.php");

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : 'areas.area';

$sql_professores = "select areas.id as area_id, areas.area, nome, professores.id as id_professor, min(periodo) as min_periodo, max(periodo) as max_periodo " .
   		" from estagiarios " .
   		" join instituicoes on estagiarios.instituicao_id = instituicoes.id left join areas on instituicoes.area = areas.id " .
   		" join professores on estagiarios.professor_id = professores.id " .
   		" group by instituicoes.area, estagiarios.professor_id " .
   		" order by $ordem";
//   		" order by areas.area";

// echo $sql_professores . "<br>";

$i = 0;
$res_professores = $db->Execute($sql_professores);
if ($res_professores === false) die ("N�o foi poss�vel consultar as tabelas");  	    
while (!$res_professores->EOF) {
	$matriz[$i]['area_id'] = $res_professores->fields['area_id'];
	$matriz[$i]['area'] = $res_professores->fields['area'];
	$matriz[$i]['id_professor'] = $res_professores->fields['id_professor'];
	$matriz[$i]['nome'] = $res_professores->fields['nome'];
	$matriz[$i]['min_periodo'] = $res_professores->fields['min_periodo'];
	$matriz[$i]['max_periodo'] = $res_professores->fields['max_periodo'];

	$i++;
	$res_professores->MoveNext();
}
/*
$sql = "select * from areas order by area";
$resultado = $db->Execute($sql);
if($resultado === false) die ("N�o foi poss�vel consultar a tabela areas");

$i = 0;
while(!$resultado->EOF) {
    $id_area = $resultado->fields["id"];
    $area    = $resultado->fields["area"];
    // Para cada �rea conto a quantidade de instituicoes
    $sql_estagio = "select area from instituicoes where area=$id_area";
    
    $res_estagio = $db->Execute($sql_estagio);
    if($res_estagio === false) die ("N�o foi poss�vel consultar a tabela instituicoes");
    $quantidade = $res_estagio->RecordCount();
    $total = $total + $quantidade;

    $matriz[$i]["id_area"] = $id_area;
    $matriz[$i]["area"]    = $area;
    $matriz[$i]["q_instituicoes"] = $quantidade;
    $i++;
    $resultado->MoveNext();
      
}
*/
$smarty = new Smarty_estagio;
$smarty->assign("areas",$matriz);
$smarty->display("areas_listar.tpl");

exit;

?>