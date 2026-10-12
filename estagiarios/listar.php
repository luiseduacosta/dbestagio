<?php
/**
 * estagiarios/listar.php — Front-controller da listagem de estágios (índice do
 * módulo). Delega toda a lógica ao EstagiariosController.
 */
require_once(__DIR__ . "/EstagiariosController.php");

$controller = new EstagiariosController();
$controller->listar();
