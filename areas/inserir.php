<?php
/**
 * areas/inserir.php — Front-controller do formulário de inserção de área.
 * Exibe o formulário (GET) e grava a nova área (POST). Delega toda a lógica
 * ao AreasController.
 */
require_once(__DIR__ . "/AreasController.php");

$controller = new AreasController();
$controller->inserir();
