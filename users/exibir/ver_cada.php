<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$u  = User::find($id);

if ($u === null) {
    die("Usuário não encontrado (id $id).");
}

$dados = $u->toArray();
$dados['role_texto']      = User::roleTexto($dados['role']);
$dados['categoria_texto'] = User::categoriaTexto($dados['categoria']);

$smarty = new Smarty_estagio;
$smarty->assign("u", $dados);
$smarty->display("users_ver_cada.tpl");

exit;

?>