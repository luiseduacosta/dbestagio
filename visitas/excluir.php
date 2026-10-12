<?php
/**
 * visitas/excluir.php — Front-controller da exclusão de visita.
 * Delega toda a lógica ao VisitasController.
 */
require_once(__DIR__ . "/VisitasController.php");

$controller = new VisitasController();
$controller->excluir();
