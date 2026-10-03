<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

// Permissões: admin (tudo) ou aluno (inscreve apenas a si mesmo).
inscricao_exigir_permissao();

// Período atual como padrão; se vier por GET, valida contra a lista de períodos.
$periodo = isset($_GET['periodo']) ? trim($_GET['periodo']) : PERIODO_ATUAL;
$periodos = inscricao_periodos();
if (!in_array($periodo, $periodos, true)) {
    $periodo = PERIODO_ATUAL;
}

$ofertas = Inscricao::ofertasPorPeriodo($periodo);

$v = array(
    'muralestagio_id' => isset($_GET['muralestagio_id']) ? (int)$_GET['muralestagio_id'] : 0,
    'registro'        => isset($_GET['registro']) ? trim($_GET['registro']) : '',
    'periodo'         => $periodo,
    'data'            => date('Y-m-d'),
);

// Aluno: a inscrição é sempre a própria (registro travado).
$alunos = array();
if ($isAluno) {
    $v['registro'] = inscricao_aluno_registro_logado();
} else {
    $alunos = Inscricao::alunosLista();
}

$smarty = new Smarty_estagio;
$smarty->assign("periodos", $periodos);
$smarty->assign("ofertas", $ofertas);
$smarty->assign("alunos", $alunos);
$smarty->assign("e_aluno", $isAluno);
$smarty->assign("v", $v);
$smarty->assign("acao", "inserir.php");
$smarty->assign("titulo", "Inscrever aluno");
$smarty->assign("e_edicao", false);
$smarty->display("inscricoes_form.tpl");

exit;

?>