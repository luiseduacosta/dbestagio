<?php
/**
 * instituicoes/atualizar.php — Front-controller da edição simples de
 * instituição. Exibe o formulário (GET) ou grava a edição (POST). Delega
 * toda a lógica ao InstituicoesController.
 */
require_once(__DIR__ . "/InstituicoesController.php");

$controller = new InstituicoesController();
$controller->atualizar();
