<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

$ins = Inscricao::buscar($id);
if ($ins === null) {
    die("Inscrição não encontrada (id $id).");
}

$smarty = new Smarty_estagio;
$smarty->assign("ins", $ins);
$smarty->display("inscricoes_ver_cada.tpl");

exit;

?>