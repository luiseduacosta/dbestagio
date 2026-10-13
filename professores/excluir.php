<?php
/**
 * professores/excluir.php — Front-controller da exclusão de professor.
 * Delega toda a lógica ao ProfessoresController.
 */
require_once(__DIR__ . "/ProfessoresController.php");

$controller = new ProfessoresController();
$controller->excluir();

?>
