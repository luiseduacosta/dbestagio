<?php
/**
 * estagiarios/excluir.php — Front-controller da exclusão de estágio.
 * Delega toda a lógica ao EstagiariosController.
 */
require_once(__DIR__ . "/EstagiariosController.php");

$controller = new EstagiariosController();
$controller->excluir();
