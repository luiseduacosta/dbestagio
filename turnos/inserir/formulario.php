<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$v = array(
    'id'    => null,
    'turno' => '',
);

$smarty = new Smarty_estagio;
$smarty->assign("v", $v);
$smarty->assign("acao", "inserir.php");
$smarty->assign("titulo", "Inserir turno");
$smarty->display("turnos_form.tpl");

exit;

?>
