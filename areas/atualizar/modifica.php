<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$id_area = isset($_REQUEST["id_area"]) ? (int)$_REQUEST["id_area"] : 0;

$area_obj = Area::find($id_area);
$area = $area_obj ? $area_obj->area : '';

$smarty = new Smarty_estagio;
$smarty->assign("id_area", $id_area);
$smarty->assign("area", $area);
$smarty->display("area_atualiza.tpl");

exit;

?>