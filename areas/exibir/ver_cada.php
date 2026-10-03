<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$area_id = isset($_REQUEST['area_id']) ? (int)$_REQUEST['area_id'] : 0;

if ($area_id <= 0) {
    die("Área não informada.");
}

$area = Area::find($area_id);

if ($area === null) {
    die("Área não encontrada (id $area_id).");
}

// Navegação entre áreas na ordem alfabética
$primeiro_id = null;
$ultimo_id   = null;
$anterior_id = null;
$proximo_id  = null;
$menos_10_id = null;
$mais_10_id  = null;
$rs_nav = Area::$db->Execute("SELECT id FROM areas ORDER BY area, id");
if ($rs_nav) {
    $ids_nav = array();
    while (!$rs_nav->EOF) {
        $ids_nav[] = (int)$rs_nav->fields['id'];
        $rs_nav->MoveNext();
    }
    $total_nav = count($ids_nav);
    $pos_nav   = array_search($area_id, $ids_nav, true);
    if ($pos_nav !== false && $total_nav > 1) {
        $primeiro_id = $ids_nav[0];
        $ultimo_id   = $ids_nav[$total_nav - 1];
        $anterior_id = $ids_nav[($pos_nav - 1 + $total_nav) % $total_nav];
        $proximo_id  = $ids_nav[($pos_nav + 1) % $total_nav];
        $menos_10_id = $ids_nav[($pos_nav - 10 + $total_nav) % $total_nav];
        $mais_10_id  = $ids_nav[($pos_nav + 10) % $total_nav];
    }
}

$smarty = new Smarty_estagio;
$smarty->assign("area_id",          $area_id);
$smarty->assign("primeiro_id",      $primeiro_id);
$smarty->assign("ultimo_id",        $ultimo_id);
$smarty->assign("anterior_id",      $anterior_id);
$smarty->assign("proximo_id",       $proximo_id);
$smarty->assign("menos_10_id",      $menos_10_id);
$smarty->assign("mais_10_id",       $mais_10_id);
$smarty->assign("area",             $area->toArray());
$smarty->assign("num_instituicoes", $area->countInstituicoes());
$smarty->assign("instituicoes",     $area->instituicoes());
$smarty->assign("pagina",           $_SERVER['PHP_SELF']);
$smarty->display("area-ver_cada.tpl");

exit;

?>