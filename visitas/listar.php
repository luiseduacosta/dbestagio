<?php
/**
 * visitas/listar.php — Front-controller da listagem de visitas (índice do
 * módulo). Endpoint público (não exige login). Delega toda a lógica ao
 * VisitasController.
 */
require_once(__DIR__ . "/VisitasController.php");

$controller = new VisitasController();
$controller->listar();
