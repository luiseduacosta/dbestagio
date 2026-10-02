<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

$dados = Estagiario::buscar($id);
if ($dados === null) {
    header("Location: listar.php");
    exit;
}

$smarty = new Smarty_estagio;
$smarty->assign("e", $dados);
$smarty->display("estagiarios_ver_cada.tpl");

exit;

?>