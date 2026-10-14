<?php
/**
 * users/ver_cada.php — Front-controller da ficha de um usuário.
 * Delega toda a lógica ao UsersController.
 */
require_once(__DIR__ . "/UsersController.php");

$controller = new UsersController();
$controller->ver_cada();

?>
