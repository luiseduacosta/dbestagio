<?php

include_once("../../setup.php");

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : 'areas_estagio.area';

$sql_professores = "select areas_estagio.id as area_id, areas_estagio.area, nome, professores.id as id_professor, min(periodo) as min_periodo, max(periodo) as max_periodo " .
   		" from estagiarios " .
   		" join areas_estagio on estagiarios.id_area = areas_estagio.id " .
   		" join professores on estagiarios.id_professor = professores.id " .
   		" group by estagiarios.id_area, estagiarios.id_professor " .
   		" order by $ordem";
//   		" order by areas_estagio.area";

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
$sql = "select * from areas_estagio order by area";
$resultado = $db->Execute($sql);
if($resultado === false) die ("N�o foi poss�vel consultar a tabela areas_estagio");

$i = 0;
while(!$resultado->EOF) {
    $id_area = $resultado->fields["id"];
    $area    = $resultado->fields["area"];
    // Para cada �rea conto a quantidade de instituicoes
    $sql_estagio = "select area from estagio where area=$id_area";
    
    $res_estagio = $db->Execute($sql_estagio);
    if($res_estagio === false) die ("N�o foi poss�vel consultar a tabela estagio");
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