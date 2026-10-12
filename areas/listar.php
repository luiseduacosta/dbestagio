<?php
/**
 * areas/listar.php — Front-controller da listagem de áreas (índice do
 * módulo). Endpoint público (não exige login). Delega toda a lógica ao
 * AreasController.
 */
require_once(__DIR__ . "/AreasController.php");

$controller = new AreasController();
$controller->listar();
