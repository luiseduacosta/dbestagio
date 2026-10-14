<?php
/**
 * turnos/inserir.php — Front-controller do cadastro de turno.
 * Exibe o formulário (GET) ou grava o novo turno (POST).
 * Delega toda a lógica ao TurnosController.
 */
require_once(__DIR__ . "/TurnosController.php");

$controller = new TurnosController();
$controller->inserir();

?>
