<?php
/**
 * instituicoes/inserir.php — Front-controller do formulário de inserção
 * de instituição. Mantém o endpoint público (action do form) e delega
 * toda a lógica ao InstituicoesController.
 */
require_once(__DIR__ . "/InstituicoesController.php");

$controller = new InstituicoesController();
$controller->inserir();
