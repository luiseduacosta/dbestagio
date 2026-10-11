<?php
/**
 * inscricoes/listar.php — Front-controller da listagem de inscrições
 * (index do módulo). Delega toda a lógica ao InscricoesController.
 */
require_once(__DIR__ . "/../mural/InscricoesController.php");

$controller = new InscricoesController();
$controller->listar();