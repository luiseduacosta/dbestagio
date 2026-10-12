<?php
/**
 * alunos/excluir.php — Front-controller da exclusão de um aluno. Exige
 * que o aluno não tenha dependências (estágios ou inscrições). Mantém
 * o endpoint público e delega toda a lógica ao AlunosController.
 */
require_once(__DIR__ . "/AlunosController.php");

$controller = new AlunosController();
$controller->excluir();
