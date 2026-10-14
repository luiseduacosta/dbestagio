<?php
/**
 * turnos/ver_cada.php — Front-controller da ficha de um turno.
 * Delega toda a lógica ao TurnosController.
 */
require_once(__DIR__ . "/TurnosController.php");

$controller = new TurnosController();
$controller->ver_cada();

?>
