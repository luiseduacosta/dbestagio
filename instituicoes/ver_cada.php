<?php
/**
 * instituicoes/ver_cada.php — Front-controller da ficha de uma instituição.
 * Trata navegação registro a registro, edição inline em dois passos e
 * vínculo de supervisor. Delega toda a lógica ao InstituicoesController.
 */
require_once(__DIR__ . "/InstituicoesController.php");

$controller = new InstituicoesController();
$controller->ver_cada();
