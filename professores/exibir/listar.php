<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$busca  = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$ordem  = isset($_GET['orderby']) ? trim($_GET['orderby']) : 'p.nome';

// Whitelist de colunas de ordenação.
$colunas_ordem = array(
    'nome'       => 'p.nome',
    'departamento' => 'p.departamento',
    'email'      => 'p.email',
    'status'     => 'p.status',
    'num_estagios' => 'num_estagios',
);
if (!isset($colunas_ordem[$ordem])) {
    $ordem = 'nome';
}
$orderby = $colunas_ordem[$ordem];

// Valida status contra a lista fechada.
$statuses = Professor::statuses();
if (!array_key_exists($status, $statuses)) {
    $status = '';
}

$professores = Professor::listar($busca, $status, $orderby);

$smarty = new Smarty_estagio;
$smarty->assign("busca", $busca);
$smarty->assign("status", $status);
$smarty->assign("statuses", $statuses);
$smarty->assign("professores", $professores);
$smarty->display("professores_listar.tpl");

exit;

?>