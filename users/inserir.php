<?php
/**
 * users/inserir.php — Front-controller do cadastro de usuário.
 * Exibe o formulário (GET) ou grava a inclusão (POST). Delega toda a
 * lógica ao UsersController.
 */
require_once(__DIR__ . "/UsersController.php");

$controller = new UsersController();
$controller->inserir();

?>
