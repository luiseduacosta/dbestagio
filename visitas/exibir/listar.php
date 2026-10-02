<?php

include_once("../../setup.php");
require_once("../../libphp/models.php");

// Filtro opcional por instituição.
$filtro_instituicao = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0;

$visitas = Visita::listar($filtro_instituicao);

$smarty = new Smarty_estagio;
$smarty->assign("visitas", $visitas);
$smarty->assign("filtro_instituicao", $filtro_instituicao);
$smarty->display("visitas_listar.tpl");

exit;

?>