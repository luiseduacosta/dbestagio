<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$busca           = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$periodo         = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
$instituicao_id  = isset($_GET['instituicao_id']) ? (int)$_GET['instituicao_id'] : 0;

// Whitelist das colunas de ordenação (evita injeção via ORDER BY).
$colunas_ordem = array(
    'nome'      => 'a.nome',
    'registro'  => 'a.registro',
    'ingresso'  => 'a.ingresso',
    'periodos_estagio' => 'periodos_estagio',
);
$ordemRaw = isset($_GET['ordem']) ? $_GET['ordem'] : 'nome';
$orderby = isset($colunas_ordem[$ordemRaw]) ? $colunas_ordem[$ordemRaw] : 'a.nome';

// Períodos e instituições válidos (lista fechada de filtros).
$periodos  = Aluno::periodos();
$instituicoes = Aluno::listarInstituicoes();
if ($periodo !== '' && !in_array($periodo, $periodos, true)) {
    $periodo = '';
}
$ids_instituicoes = array();
foreach ($instituicoes as $inst) {
    $ids_instituicoes[] = $inst['id'];
}
if ($instituicao_id > 0 && !in_array($instituicao_id, $ids_instituicoes, true)) {
    $instituicao_id = 0;
}

// A ordenação é feita pelo DataTables no navegador: $orderby é usado apenas
// como ordenação inicial no banco.
$alunos = Aluno::listar($busca, $periodo, $instituicao_id, $orderby);

$smarty = new Smarty_estagio;
$smarty->assign("busca", $busca);
$smarty->assign("periodo", $periodo);
$smarty->assign("periodos", $periodos);
$smarty->assign("instituicao_id", $instituicao_id);
$smarty->assign("instituicoes", $instituicoes);
$smarty->assign("alunos", $alunos);
$smarty->display("alunos_listar.tpl");

exit;

?>