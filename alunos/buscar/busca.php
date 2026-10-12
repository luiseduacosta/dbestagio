<?php
/**
 * alunos/buscar/busca.php — Front-controller do formulário de busca de aluno.
 *
 * Exibe o formulário de busca e, em caso de submissão POST (busca por DRE),
 * invoca o AlunosController para realizar a consulta e exibir resultados.
 */
require_once __DIR__ . '/../AlunosController.php';

$controller = new AlunosController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$controller->buscar();
} else {
	$smarty = new Smarty_estagio;
	$smarty->display("alunos-busca.tpl");
}