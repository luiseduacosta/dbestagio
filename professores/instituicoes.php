<?php
/**
 * professores/instituicoes.php — Front-controller da listagem de instituições
 * vinculadas aos professores por meio de estágios. Endpoint público. Delega
 * toda a lógica ao ProfessoresController.
 */
require_once(__DIR__ . "/ProfessoresController.php");

$controller = new ProfessoresController();
$controller->instituicoes();

?>
