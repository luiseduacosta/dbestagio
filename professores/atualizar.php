<?php
/**
 * professores/atualizar.php — Front-controller da edição de professor.
 * Exibe o formulário (GET) ou grava a alteração (POST). Delega toda a
 * lógica ao ProfessoresController.
 */
require_once(__DIR__ . "/ProfessoresController.php");

$controller = new ProfessoresController();
$controller->atualizar();

?>
