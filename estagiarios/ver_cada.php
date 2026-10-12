<?php
/**
 * estagiarios/ver_cada.php — Front-controller da ficha de um estágio.
 * Delega toda a lógica ao EstagiariosController.
 */
require_once(__DIR__ . "/EstagiariosController.php");

$controller = new EstagiariosController();
$controller->ver_cada();
