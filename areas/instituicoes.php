<?php
/**
 * areas/instituicoes.php — Front-controller da listagem de instituições de
 * uma área. Endpoint público (não exige login). Delega toda a lógica ao
 * AreasController.
 */
require_once(__DIR__ . "/AreasController.php");

$controller = new AreasController();
$controller->instituicoes();
