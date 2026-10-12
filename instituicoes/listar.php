<?php
/**
 * instituicoes/listar.php — Front-controller da listagem de instituições
 * (index do módulo). Endpoint público (não exige login). Delega toda a
 * lógica ao InstituicoesController.
 */
require_once(__DIR__ . "/InstituicoesController.php");

$controller = new InstituicoesController();
$controller->listar();
