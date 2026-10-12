<?php
/**
 * estagiarios/atualizar.php — Front-controller da edição de estágio. Exibe o
 * formulário (GET) ou grava a alteração (POST). Delega toda a lógica ao
 * EstagiariosController.
 */
require_once(__DIR__ . "/EstagiariosController.php");

$controller = new EstagiariosController();
$controller->atualizar();
