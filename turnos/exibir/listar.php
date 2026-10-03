<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$turnos = Turno::listar();

$smarty = new Smarty_estagio;
$smarty->assign("turnos", $turnos);
$smarty->display("turnos_listar.tpl");

exit;

?>
