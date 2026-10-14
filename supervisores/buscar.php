<?php
/**
 * supervisores/buscar.php — Front-controller para busca de supervisores por nome.
 * Delega toda a lógica ao SupervisoresController.
 */
require_once(__DIR__ . "/SupervisoresController.php");

$controller = new SupervisoresController();
$controller->buscar();

?>
