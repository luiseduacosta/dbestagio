<?php

include_once("../autentica.inc");
require_once("../libphp/models.php");

$opcao = isset($_GET['opcao']) ? $_GET['opcao'] : '';

// Lista completa de áreas (ordem alfabética).
$areas_objs = Area::seleciona();

$id_areas = array();
$areas    = array();
foreach ($areas_objs as $a) {
    $id_areas[] = $a['id'];
    $areas[]    = $a['area'];
}

$smarty = new Smarty_estagio;
$smarty->assign("opcao", $opcao);
$smarty->assign("id_areas", $id_areas);
$smarty->assign("areas", $areas);
$smarty->display("area_seleciona.tpl");

exit;

?>