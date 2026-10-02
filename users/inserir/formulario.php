<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$smarty = new Smarty_estagio;

$dados = array(
    'id'            => null,
    'email'         => '',
    'nome'          => '',
    'role'          => 'aluno',
    'categoria'     => '2',
    'identificacao' => '',
    'ativo'         => 1,
    'password'      => '',
);

users_form_options();
$smarty->assign('opts', users_form_options());
$smarty->assign("v", $dados);
$smarty->assign("acao", "inserir.php");
$smarty->assign("titulo", "Inserir usuário");
$smarty->assign("e_edicao", false);
$smarty->display("users_form.tpl");

exit;

?>