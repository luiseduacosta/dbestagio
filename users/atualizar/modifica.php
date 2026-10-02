<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$u  = User::find($id);

if ($u === null) {
    die("Usuário não encontrado (id $id).");
}

$saida = $u->toArray();
foreach (array('password', 'criado_em', 'atualizado_em') as $ignorar) {
    unset($saida[$ignorar]);
}
$saida['password'] = '';

$smarty = new Smarty_estagio;
$smarty->assign('opts', users_form_options());
$smarty->assign("v", $saida);
$smarty->assign("acao", "atualiza.php");
$smarty->assign("titulo", "Editar usuário");
$smarty->assign("e_edicao", true);
$smarty->display("users_form.tpl");

exit;

?>