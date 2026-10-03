<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$turno = Turno::find($id);

if ($turno === null) {
    die("Turno não encontrado (id $id).");
}

$v = array(
    'id'    => $turno->getKey(),
    'turno' => $turno->turno,
);

$smarty = new Smarty_estagio;
$smarty->assign("v", $v);
$smarty->assign("acao", "atualiza.php");
$smarty->assign("titulo", "Editar turno");
$smarty->display("turnos_form.tpl");

exit;

?>
