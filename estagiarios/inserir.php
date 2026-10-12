<?php
/**
 * estagiarios/inserir.php — Front-controller do formulário de inserção de
 * estágio. Exibe o formulário (GET) e grava o novo estágio (POST). Delega
 * toda a lógica ao EstagiariosController.
 */
require_once(__DIR__ . "/EstagiariosController.php");

$controller = new EstagiariosController();
$controller->inserir();
