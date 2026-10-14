<?php
/**
 * supervisores/listar.php — Front-controller da listagem de supervisores.
 * Delega toda a lógica ao SupervisoresController.
 */
require_once(__DIR__ . "/SupervisoresController.php");

$controller = new SupervisoresController();
$controller->listar();

?>
