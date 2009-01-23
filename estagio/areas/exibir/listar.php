<?php

include_once("../../db.inc");
include_once("../../setup.php");

$sql = "select * from areas_estagio order by area";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela areas_estagio");

$i = 0;
while(!$resultado->EOF)
{
    $id_area = $resultado->fields["id"];
    $area    = $resultado->fields["area"];
    // Para cada área conto a quantidade de instituicoes
    $sql_estagio = "select area from estagio where area=$id_area";
    $res_estagio = $db->Execute($sql_estagio);
    if($res_estagio === false) die ("Não foi possível consultar a tabela estagio");
    $quantidade = $res_estagio->RecordCount();
    $total = $total + $quantidade;

    $matriz[$i]["id_area"] = $id_area;
    $matriz[$i]["area"]    = $area;
    $matriz[$i]["q_instituicoes"] = $quantidade;
    $i++;
    $resultado->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("areas",$matriz);
$smarty->assign("total",$total);
$smarty->display("areas_listar.tlp");

exit;

?>