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
$estagiarios    = Professor::estagiarios($professor_id, $ordem);

// Navegação: professor anterior/próximo na ordem alfabética (com wrap-around).
$primeiro_id = null;
$ultimo_id   = null;
$anterior_id = null;
$proximo_id  = null;
$menos_10_id = null;
$mais_10_id  = null;
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
        $primeiro_id = $ids_nav[0];
        $ultimo_id   = $ids_nav[$total_nav - 1];
        $anterior_id = $ids_nav[($pos_nav - 1 + $total_nav) % $total_nav];
        $proximo_id  = $ids_nav[($pos_nav + 1) % $total_nav];
        $menos_10_id = $ids_nav[($pos_nav - 10 + $total_nav) % $total_nav];
        $mais_10_id  = $ids_nav[($pos_nav + 10) % $total_nav];
    }
}

$smarty = new Smarty_estagio;
$smarty->assign("professor_id",   $professor_id);
$smarty->assign("primeiro_id",    $primeiro_id);
$smarty->assign("ultimo_id",      $ultimo_id);
$smarty->assign("anterior_id",    $anterior_id);
$smarty->assign("proximo_id",     $proximo_id);
$smarty->assign("menos_10_id",    $menos_10_id);
$smarty->assign("mais_10_id",     $mais_10_id);
$smarty->assign("professor_nome", $professor_nome);
$smarty->assign("cpf",              $dados['cpf']);
$smarty->assign("siape",            $dados['siape']);
$smarty->assign("cress",            $dados['cress']);
$smarty->assign("regiao",           $dados['regiao']);
// Exibição dos telefones: se o número já vier com DDD embutido, mostra como está.
$telefone_exib = (string)$dados['telefone'];
if ($telefone_exib !== '' && strpos($telefone_exib, '(') === false) {
    $telefone_exib = '(' . (int)$dados['codigo_telefone'] . ') ' . $telefone_exib;
}
$celular_exib = (string)$dados['celular'];
if ($celular_exib !== '' && strpos($celular_exib, '(') === false) {
    $celular_exib = '(' . (int)$dados['codigo_celular'] . ') ' . $celular_exib;
}

$smarty->assign("codigo_telefone",  $dados['codigo_telefone']);
$smarty->assign("telefone",         $telefone_exib);
$smarty->assign("codigo_celular",   $dados['codigo_celular']);
$smarty->assign("celular",          $celular_exib);
$smarty->assign("email",            $dados['email']);
$smarty->assign("curriculolattes",  $dados['curriculolattes']);
$smarty->assign("atualizacaolattes",$dados['atualizacaolattes']);
$smarty->assign("dataingresso",     $dados['dataingresso']);
$smarty->assign("tipocargo",        $dados['tipocargo']);
$smarty->assign("departamento",     $dados['departamento']);
$smarty->assign("dataegresso",      $dados['dataegresso']);
$smarty->assign("motivoegresso",    $dados['motivoegresso']);
$smarty->assign("status",           $dados['status']);
$smarty->assign("observacoes",      $dados['observacoes']);
$smarty->assign("created",          $dados['created']);
$smarty->assign("modified",         $dados['modified']);
$smarty->assign("num_estagios",   (int)$dados['num_estagios']);
$smarty->assign("num_instituicoes", (int)$dados['num_instituicoes']);
$smarty->assign("estagiarios",    $estagiarios);
$smarty->assign("ordem",          $ordem);
$smarty->display("professores_ver_cada.tpl");

exit;

?>