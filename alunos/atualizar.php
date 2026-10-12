<?php
/**
 * alunos/atualizar.php — Front-controller da edição de aluno. Exibe o
 * formulário (GET) ou grava a edição (POST). Em compatibilidade com o
 * script legado, também atualiza um único registro de estagiário quando
 * o POST traz `estagiario_id` (double duty). Delega toda a lógica ao
 * AlunosController.
 */
require_once(__DIR__ . "/AlunosController.php");

$controller = new AlunosController();
$controller->atualizar();
