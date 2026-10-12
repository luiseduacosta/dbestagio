<?php
/**
 * alunos/listar.php — Front-controller do índice/lista de alunos (com
 * filtros por busca, período e instituição de estágio). Mantém o
 * endpoint público e delega toda a lógica ao AlunosController.
 */
require_once(__DIR__ . "/AlunosController.php");

$controller = new AlunosController();
$controller->listar();
