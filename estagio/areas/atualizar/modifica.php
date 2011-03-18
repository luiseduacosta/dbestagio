<?php

include_once("../../autentica.inc");

include_once("../../setup.php");

$smarty = new Smarty_estagio;

$id_area = $_GET["id_area"];
if(empty($id_area))
    $id_area = $_POST["id_area"];

$sql = "select * from areas_estagio where id=$id_area";
$resultado = $db->Execute($sql);

if ($resultado === false) die ("Nao foi possivel consultar a tabela areas_estagio");

while (!$resultado->EOF) {
    $area = $resultado->fields["area"];
    $resultado->MoveNext();
}

$smarty->assign("id_area",$id_area);
$smarty->assign("area",$area);
$smarty->display("area_atualiza.tlp");

exit;

?>