<?php
/**
 * alunos/ver_cada.php — Front-controller da ficha de um aluno (com
 * navegação registro a registro). Mantém o endpoint público e delega
 * toda a lógica ao AlunosController.
 */
require_once(__DIR__ . "/AlunosController.php");

$controller = new AlunosController();
$controller->ver_cada();
