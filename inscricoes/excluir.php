<?php
/**
 * inscricoes/excluir.php — Front-controller da exclusão de inscrição.
 * Delega toda a lógica ao InscricoesController.
 */
require_once(__DIR__ . "/InscricoesController.php");

$controller = new InscricoesController();
$controller->excluir();