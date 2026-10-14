<?php
/**
 * supervisores/atualizar.php — Front-controller da edição de supervisor.
 * Exibe o formulário (GET) ou grava a alteração (POST).
 * Delega toda a lógica ao SupervisoresController.
 */
require_once(__DIR__ . "/SupervisoresController.php");

$controller = new SupervisoresController();
$controller->atualizar();

?>
