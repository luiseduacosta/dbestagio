<?php
/**
 * inscricoes/ver_cada.php — Front-controller da ficha de uma inscrição.
 * Delega toda a lógica ao InscricoesController.
 */
require_once(__DIR__ . "/InscricoesController.php");

$controller = new InscricoesController();
$controller->ver_cada();