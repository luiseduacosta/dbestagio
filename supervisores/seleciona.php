<?php
/**
 * supervisores/seleciona.php — Front-controller para seleção rápida de supervisor.
 * Delega toda a lógica ao SupervisoresController.
 */
require_once(__DIR__ . "/SupervisoresController.php");

$controller = new SupervisoresController();
$controller->seleciona();

?>