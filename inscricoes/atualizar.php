<?php
/**
 * inscricoes/atualizar.php — Front-controller do formulário de edição
 * de inscrição (GET) e gravação da edição (POST). Delega toda a lógica
 * ao InscricoesController.
 */
require_once(__DIR__ . "/InscricoesController.php");

$controller = new InscricoesController();
$controller->atualizar();