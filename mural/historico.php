<?php
/**
 * historico.php — Ponto de entrada do histórico do mural.
 *
 * Lê os parâmetros da URL e despacha para o método correspondente do
 * HistoricoController. Os links no template muralestagios-historico.tpl
 * continuam apontando para este arquivo; a lógica foi movida para a classe.
 *
 *   (sem parâmetros)        -> resumo por período
 *   ?periodo=X              -> alunos do período X
 *   ?selecao=X              -> instituições/vagas do período X
 *   ?periodo_sem_estagio=X  -> alunos sem estágio no período X
 */

require("../setup.php");
include_once(__DIR__ . "/../autentica.inc");
require_once(__DIR__ . "/../libphp/models.php");
require_once(__DIR__ . "/HistoricoController.php");

$periodo             = isset($_REQUEST['periodo'])             ? $_REQUEST['periodo']             : null;
$selecao             = isset($_REQUEST['selecao'])             ? $_REQUEST['selecao']             : null;
$periodo_sem_estagio = isset($_REQUEST['periodo_sem_estagio']) ? $_REQUEST['periodo_sem_estagio'] : null;

$historico = new HistoricoController();

if ($periodo) {
	$historico->alunosPeriodo($periodo);
} elseif ($selecao) {
	$historico->instituicoesPeriodo($selecao);
} elseif ($periodo_sem_estagio) {
	$historico->alunosSemEstagio($periodo_sem_estagio);
} else {
	$historico->resumo();
}
