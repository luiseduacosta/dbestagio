<?php
/**
 * visitas/inserir.php — Front-controller do formulário de inserção de visita.
 * Exibe o formulário (GET) e grava a nova visita (POST). Delega toda a lógica
 * ao VisitasController.
 */
require_once(__DIR__ . "/VisitasController.php");

$controller = new VisitasController();
$controller->inserir();
