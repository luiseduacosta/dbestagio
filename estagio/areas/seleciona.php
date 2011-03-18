<?php

include_once("../autentica.inc");

include_once("../setup.php");

$opcao = $_GET['opcao'];

$smarty = new Smarty_estagio;

$sql = "select * from areas_estagio order by area";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Nao foi possivel consultar a tabela areas_estagio");

$i = 0;
while(!$resultado->EOF)
{
    $id_areas[$i] = $resultado->fields["id"];
    $areas[$i] = $resultado->fields["area"];
    $i++;
    $resultado->MoveNext();
}

$smarty->assign("opcao",$opcao);
$smarty->assign("id_areas",$id_areas);
$smarty->assign("areas",$areas);
$smarty->display("area_seleciona.tlp");

exit;

?>