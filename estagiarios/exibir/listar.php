<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$periodo  = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
$busca    = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$ordem    = isset($_GET['orderby']) ? trim($_GET['orderby']) : 'periodo_desc';

// Whitelist de colunas de ordenação.
$colunas_ordem = array(
    'periodo_desc' => 'e.periodo DESC, a.nome',
    'periodo_asc'  => 'e.periodo ASC, a.nome',
    'aluno'        => 'a.nome',
    'registro'     => 'e.registro',
    'instituicao'  => 'i.instituicao',
    'supervisor'   => 's.nome',
    'professor'    => 'p.nome',
);
if (!isset($colunas_ordem[$ordem])) {
    $ordem = 'periodo_desc';
}
$orderby = $colunas_ordem[$ordem];

// Valida período contra a lista fechada.
$periodos = Estagiario::periodos();
if ($periodo !== '' && !in_array($periodo, $periodos, true)) {
    $periodo = '';
}

$estagiarios = Estagiario::listar($periodo, $busca, $orderby);

$smarty = new Smarty_estagio;
$smarty->assign("periodo", $periodo);
$smarty->assign("periodos", $periodos);
$smarty->assign("busca", $busca);
$smarty->assign("estagiarios", $estagiarios);
$smarty->display("estagiarios_listar.tpl");

exit;

?>