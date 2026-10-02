<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$periodo  = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
$aluno_id = isset($_GET['aluno_id']) ? (int)$_GET['aluno_id'] : 0;

$v = array(
    'aluno_id'         => $aluno_id,
    'periodo'          => $periodo,
    'nivel'            => '',
    'tc'               => 0,
    'tc_solicitacao'   => '',
    'instituicao_id'   => 0,
    'supervisor_id'    => 0,
    'professor_id'     => 0,
    'nota'             => '',
    'ch'               => '',
    'ajuste2020'       => '0',
    'benetransporte'   => 0,
    'benealimentacao'  => 0,
    'benebolsa'        => '',
    'observacoes'      => '',
);

$alunos       = Estagiario::alunos();
$instituicoes = Instituicao::listar_todas();
$supervisores = Supervisor::listar('nome');
$professores  = Professor::listar('', '', 'p.nome');
$niveis       = Estagiario::niveis();

$smarty = new Smarty_estagio;
$smarty->assign("alunos", $alunos);
$smarty->assign("instituicoes", $instituicoes);
$smarty->assign("supervisores", $supervisores);
$smarty->assign("professores", $professores);
$smarty->assign("niveis", $niveis);
$smarty->assign("v", $v);
$smarty->assign("acao", "inserir.php");
$smarty->assign("titulo", "Novo estágio");
$smarty->assign("e_edicao", false);
$smarty->display("estagiarios_form.tpl");

exit;

?>