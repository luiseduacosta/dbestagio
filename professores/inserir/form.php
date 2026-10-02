<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$statuses = Professor::statuses();
array_shift($statuses); // remove "Todos" — não é um status válido para salvar

$v = array(
    'id'               => 0,
    'nome'             => '',
    'cpf'              => '',
    'siape'            => '',
    'cress'            => '',
    'regiao'           => '',
    'codigo_telefone'  => '21',
    'telefone'         => '',
    'codigo_celular'   => '21',
    'celular'          => '',
    'email'            => '',
    'curriculolattes'  => '',
    'atualizacaolattes'=> '',
    'dataingresso'     => '',
    'tipocargo'        => '',
    'departamento'     => '',
    'dataegresso'      => '',
    'motivoegresso'    => '',
    'status'           => 'ativo',
    'observacoes'      => '',
);

$smarty = new Smarty_estagio;
$smarty->assign("statuses", $statuses);
$smarty->assign("v", $v);
$smarty->assign("acao", "inserir.php");
$smarty->assign("titulo", "Novo professor");
$smarty->assign("e_edicao", false);
$smarty->display("professores_form.tpl");

exit;

?>