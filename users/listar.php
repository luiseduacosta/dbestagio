<?php
/**
 * users/listar.php — Front-controller da listagem de usuários
 * (index do módulo). Delega toda a lógica ao UsersController.
 */
require_once(__DIR__ . "/UsersController.php");

$controller = new UsersController();
$controller->listar();

?>
