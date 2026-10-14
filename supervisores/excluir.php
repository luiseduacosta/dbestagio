<?php
/**
 * supervisores/excluir.php — Front-controller da exclusão de supervisor.
 * Delega toda a lógica ao SupervisoresController.
 */
require_once(__DIR__ . "/SupervisoresController.php");

$controller = new SupervisoresController();
$controller->excluir();

?>
