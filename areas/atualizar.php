<?php
/**
 * areas/atualizar.php — Front-controller da edição de área. Exibe o
 * formulário (GET) ou grava a alteração (POST). Delega toda a lógica ao
 * AreasController.
 */
require_once(__DIR__ . "/AreasController.php");

$controller = new AreasController();
$controller->atualizar();
