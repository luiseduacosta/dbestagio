<?php
/**
 * users/excluir.php — Front-controller da exclusão de usuário.
 * Delega toda a lógica ao UsersController.
 */
require_once(__DIR__ . "/UsersController.php");

$controller = new UsersController();
$controller->excluir();

?>
