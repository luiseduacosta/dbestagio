<?php
/**
 * users/atualizar.php — Front-controller da edição de usuário.
 * Exibe o formulário (GET) ou grava a alteração (POST). Delega toda a
 * lógica ao UsersController.
 */
require_once(__DIR__ . "/UsersController.php");

$controller = new UsersController();
$controller->atualizar();

?>
