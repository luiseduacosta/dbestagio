<?php
/**
 * visitas/atualizar.php — Front-controller da edição de visita. Exibe o
 * formulário (GET) ou grava a alteração (POST). Delega toda a lógica ao
 * VisitasController.
 */
require_once(__DIR__ . "/VisitasController.php");

$controller = new VisitasController();
$controller->atualizar();
