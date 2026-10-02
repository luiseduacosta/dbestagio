<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$v = Visita::find($id);

if ($v === null) {
    die("Visita não encontrada (id $id).");
}

$smarty = new Smarty_estagio;

$smarty->assign("v", $v->toArray());
$smarty->assign("nome_instituicao", $v->instituicaoNome());
$smarty->assign("nome_professor", $v->professorNome());
$smarty->display("visitas_ver_cada.tpl");

exit;

?>