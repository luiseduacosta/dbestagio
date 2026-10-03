<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$area_id = isset($_REQUEST["area_id"]) ? (int)$_REQUEST["area_id"] : 0;

$area_obj = Area::find($area_id);
$area = $area_obj ? $area_obj->area : '';

$smarty = new Smarty_estagio;
$smarty->assign("area_id", $area_id);
$smarty->assign("area", $area);
$smarty->display("area_atualiza.tpl");

exit;

?>