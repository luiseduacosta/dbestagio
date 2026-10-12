<?php
/**
 * visitas/ver_cada.php — Front-controller da ficha de uma visita.
 * Delega toda a lógica ao VisitasController.
 */
require_once(__DIR__ . "/VisitasController.php");

$controller = new VisitasController();
$controller->ver_cada();
