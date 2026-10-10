<?php

include_once(__DIR__ . "/../../setup.php");
require_once(__DIR__ . "/../../libphp/models.php");

// O seletor de período é exclusivo do administrador. Para os demais usuários a
// página sempre mostra o período atual (e ignora o parâmetro ?periodo da URL).
$is_admin = false;
$usuario_cookie = isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : '';
if ($usuario_cookie !== '') {
    $role = Instituicao::$db->GetOne("SELECT role FROM users WHERE email = ?", array($usuario_cookie));
    $is_admin = ($role === 'admin');
}

// Período selecionado (filtra a lista). Vazio = todos os períodos.
// Somente o admin pode escolher o período; os demais usam o período atual fixo.
if ($is_admin) {
    $periodo = isset($_GET['periodo']) ? trim($_GET['periodo']) : PERIODO_ATUAL;
} else {
    $periodo = PERIODO_ATUAL;
}

// Períodos disponíveis (preenchem o seletor do admin) e servem de lista
// fechada de valores aceitos (proteção contra SQL injection).
$periodos = Mural::periodos();

// Somente aceita período que exista no mural.
if ($periodo !== '' && !in_array($periodo, $periodos, true)) {
    $periodo = PERIODO_ATUAL;
}

// Lista de ofertas do mural do período selecionado (inscritos calculados no SQL).
$ofertas = Mural::listarMuralPorPeriodo($periodo);

// Totais do período selecionado.
$total_vagas  = 0;
$total_ofertas = count($ofertas);
foreach ($ofertas as $o) {
    $total_vagas += $o['vagas'];
}

$smarty = new Smarty_estagio;

$smarty->assign("is_admin", $is_admin);
$smarty->assign("periodo", $periodo);
$smarty->assign("periodos", $periodos);
$smarty->assign("ofertas", $ofertas);
$smarty->assign("total_ofertas", $total_ofertas);
$smarty->assign("total_vagas", $total_vagas);
$smarty->display("mural_listar.tpl");

exit;

?>