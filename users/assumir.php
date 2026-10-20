<?php
/**
 * users/assumir.php — Front-controller para impersonação.
 * Apenas admin pode usar. Troca a sessão para o usuário alvo.
 */
require_once(__DIR__ . "/UsersController.php");

$controller = new UsersController();
$controller->assumir();
