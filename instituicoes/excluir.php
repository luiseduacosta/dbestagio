<?php
/**
 * instituicoes/excluir.php — Front-controller da exclusão de instituição.
 * Verifica dependências e, se não houver, apaga o registro. Delega toda a
 * lógica ao InstituicoesController.
 */
require_once(__DIR__ . "/InstituicoesController.php");

$controller = new InstituicoesController();
$controller->excluir();
