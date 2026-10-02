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

$smarty = new Smarty_estagio;
$smarty->assign("professor_id",   $professor_id);
$smarty->assign("professor_nome", $professor_nome);
$smarty->assign("num_estagios",   (int)$dados['num_estagios']);
$smarty->assign("num_instituicoes", (int)$dados['num_instituicoes']);
$smarty->assign("instituicoes",   $instituicoes);
$smarty->assign("estagiarios",    $estagiarios);
$smarty->assign("ordem",          $ordem);
$smarty->display("professores_ver_cada.tpl");

exit;

?>