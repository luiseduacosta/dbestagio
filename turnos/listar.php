<?php
/**
 * turnos/listar.php — Front-controller da listagem de turnos.
 * Delega toda a lógica ao TurnosController.
 */
require_once(__DIR__ . "/TurnosController.php");

$controller = new TurnosController();
$controller->listar();

?>
