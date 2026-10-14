<?php
/**
 * turnos/excluir.php — Front-controller da exclusão de turno.
 * Delega toda a lógica ao TurnosController.
 */
require_once(__DIR__ . "/TurnosController.php");

$controller = new TurnosController();
$controller->excluir();

?>
