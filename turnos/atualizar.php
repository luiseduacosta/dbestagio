<?php
/**
 * turnos/atualizar.php — Front-controller da edição de turno.
 * Exibe o formulário (GET) ou grava a alteração (POST).
 * Delega toda a lógica ao TurnosController.
 */
require_once(__DIR__ . "/TurnosController.php");

$controller = new TurnosController();
$controller->atualizar();

?>
