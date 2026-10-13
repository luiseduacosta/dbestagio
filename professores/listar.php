<?php
/**
 * professores/listar.php — Front-controller da listagem de professores
 * (index do módulo). Delega toda a lógica ao ProfessoresController.
 */
require_once(__DIR__ . "/ProfessoresController.php");

$controller = new ProfessoresController();
$controller->listar();

?>
