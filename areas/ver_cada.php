<?php
/**
 * areas/ver_cada.php — Front-controller da ficha de uma área (navegação
 * registro a registro). Delega toda a lógica ao AreasController.
 */
require_once(__DIR__ . "/AreasController.php");

$controller = new AreasController();
$controller->ver_cada();
