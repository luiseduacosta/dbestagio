<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$turno = Turno::find($id);

if ($turno === null) {
    die("Turno não encontrado (id $id).");
}

$smarty = new Smarty_estagio;
$smarty->assign("t", $turno->toArray());
$smarty->assign("num_alunos", $turno->countAlunos());
$smarty->assign("alunos", $turno->alunos());
$smarty->display("turnos_ver_cada.tpl");

exit;

?>
