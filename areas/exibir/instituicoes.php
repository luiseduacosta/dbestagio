<?php

include_once("../../setup.php");
require_once("../../libphp/models.php");

$area_id = isset($_GET['area_id']) ? (int)$_GET['area_id'] : 0;
$ordem   = isset($_GET['ordem']) ? $_GET['ordem'] : 'instituicao';

// Whitelist de colunas de ordenação seguras.
$ordens_validas = array(
    'instituicao' => 'e.instituicao',
    'turma'       => 'turma',
    'endereco'    => 'e.endereco',
    'telefone'    => 'e.telefone',
);
if (!isset($ordens_validas[$ordem])) {
    $ordem = 'instituicao';
}

$area_obj = Area::find($area_id);
$nome_area = $area_obj ? $area_obj->area : '';
$matriz    = $area_obj ? $area_obj->instituicoes() : array();

// Aplica a ordenação selecionada (em memória, segura).
if ($matriz) {
    usort($matriz, function ($a, $b) use ($ordem) {
        $va = isset($a[$ordem]) ? (string)$a[$ordem] : '';
        $vb = isset($b[$ordem]) ? (string)$b[$ordem] : '';
        return strnatcasecmp($va, $vb);
    });
    $matriz = array_values($matriz);
}

$smarty = new Smarty_estagio;
$smarty->assign("area_id", $area_id);
$smarty->assign("nome_area", $nome_area);
$smarty->assign("instituicoes", $matriz);
$smarty->display("area_instituicoes.tpl");

exit;

?>