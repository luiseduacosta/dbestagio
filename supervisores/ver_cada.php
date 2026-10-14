<?php
/**
 * supervisores/ver_cada.php — Front-controller da ficha de um supervisor.
 * Delega toda a lógica ao SupervisoresController.
 */
require_once(__DIR__ . "/SupervisoresController.php");

$controller = new SupervisoresController();
$controller->ver_cada();

?>
