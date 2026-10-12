<?php
/**
 * inscricoes/inserir.php — Front-controller do formulário de inserção
 * de inscrição. Mantém o endpoint público (action do form) e delega
 * toda a lógica ao InscricoesController.
 */
require_once(__DIR__ . "/InscricoesController.php");

$controller = new InscricoesController();
$controller->inserir();