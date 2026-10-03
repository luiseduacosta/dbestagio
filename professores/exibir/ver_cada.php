<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$professor_id = isset($_REQUEST['professor_id']) ? (int)$_REQUEST['professor_id'] : 0;
$ordem = isset($_REQUEST['ordem']) ? trim($_REQUEST['ordem']) : 'e.periodo';

$dados = Professor::buscar($professor_id);
if ($dados === null) {
    header("Location: listar.php");
    exit;
}

$professor_nome = $dados['nome'];
$instituicoes   = Professor::instituicoes($professor_id);
$estagiarios    = Professor::estagiarios($professor_id, $ordem);

// Navegação: professor anterior/próximo na ordem alfabética (com wrap-around).
$anterior_id = null;
$proximo_id  = null;
$rs_nav = Professor::$db->Execute("SELECT id FROM professores ORDER BY nome, id");
if ($rs_nav) {
    $ids_nav = array();
    while (!$rs_nav->EOF) {
        $ids_nav[] = (int)$rs_nav->fields['id'];
        $rs_nav->MoveNext();
    }
    $total_nav = count($ids_nav);
    $pos_nav   = array_search($professor_id, $ids_nav, true);
    if ($pos_nav !== false && $total_nav > 1) {
        $anterior_id = $ids_nav[($pos_nav - 1 + $total_nav) % $total_nav];
        $proximo_id  = $ids_nav[($pos_nav + 1) % $total_nav];
    }
}

$smarty = new Smarty_estagio;
$smarty->assign("professor_id",   $professor_id);
$smarty->assign("anterior_id",    $anterior_id);
$smarty->assign("proximo_id",     $proximo_id);
$smarty->assign("professor_nome", $professor_nome);
$smarty->assign("num_estagios",   (int)$dados['num_estagios']);
$smarty->assign("num_instituicoes", (int)$dados['num_instituicoes']);
$smarty->assign("instituicoes",   $instituicoes);
$smarty->assign("estagiarios",    $estagiarios);
$smarty->assign("ordem",          $ordem);
$smarty->display("professores_ver_cada.tpl");

exit;

?>