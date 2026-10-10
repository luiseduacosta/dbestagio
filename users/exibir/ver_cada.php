<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$dados = User::verCadaCompleto($id);

if ($dados === null) {
    die("Usuário não encontrado (id $id).");
}

$smarty = new Smarty_estagio;
$smarty->assign("u", $dados);
$smarty->display("users_ver_cada.tpl");

exit;

?>