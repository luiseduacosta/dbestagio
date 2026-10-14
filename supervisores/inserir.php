<?php
/**
 * supervisores/inserir.php — Front-controller do cadastro de supervisor.
 * Exibe o formulário (GET) ou grava a inclusão (POST).
 * Delega toda a lógica ao SupervisoresController.
 */
require_once(__DIR__ . "/SupervisoresController.php");

$controller = new SupervisoresController();
$controller->inserir();

?>
