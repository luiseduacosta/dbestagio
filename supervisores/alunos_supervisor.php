<?php
/**
 * supervisores/alunos_supervisor.php — Front-controller para exibir os alunos
 * supervisionados por um supervisor. Delega toda a lógica ao SupervisoresController.
 */
require_once(__DIR__ . "/SupervisoresController.php");

$controller = new SupervisoresController();
$controller->alunos_supervisor();

?>
