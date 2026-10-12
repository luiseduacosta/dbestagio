<?php
/**
 * areas/seleciona.php — Front-controller do seletor de áreas
 * (modificar/excluir). Delega toda a lógica ao AreasController.
 */
require_once(__DIR__ . "/AreasController.php");

$controller = new AreasController();
$controller->seleciona();
