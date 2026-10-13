<?php
/**
 * professores/ver_cada.php — Front-controller da ficha de um professor.
 * Trata navegação registro a registro e listagem de estagiários. Delega
 * toda a lógica ao ProfessoresController.
 */
require_once(__DIR__ . "/ProfessoresController.php");

$controller = new ProfessoresController();
$controller->ver_cada();

?>
