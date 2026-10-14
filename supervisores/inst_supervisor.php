<?php
/**
 * supervisores/inst_supervisor.php — Front-controller para desvincular
 * instituição de supervisor. Delega toda a lógica ao SupervisoresController.
 */
require_once(__DIR__ . "/SupervisoresController.php");

$controller = new SupervisoresController();
$controller->inst_supervisor();

?>
