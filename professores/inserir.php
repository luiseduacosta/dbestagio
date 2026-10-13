<?php
/**
 * professores/inserir.php — Front-controller do cadastro de professor.
 * Exibe o formulário (GET) ou grava a inclusão (POST). Delega toda a
 * lógica ao ProfessoresController.
 */
require_once(__DIR__ . "/ProfessoresController.php");

$controller = new ProfessoresController();
$controller->inserir();

?>
