<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$usuarios = User::listar();

$smarty = new Smarty_estagio;
$smarty->assign("usuarios", $usuarios);
$smarty->display("users_listar.tpl");

exit;

?>