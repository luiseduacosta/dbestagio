<?php
/**
 * alunos/inserir.php — Front-controller do formulário de inserção de
 * aluno. Mantém o endpoint público (action do form e link do menu) e
 * delega toda a lógica ao AlunosController.
 */
require_once(__DIR__ . "/AlunosController.php");

$controller = new AlunosController();
$controller->inserir();
