<?php
/**
 * configuracoes/ver_cada.php — Exibe as configurações do sistema.
 */
require_once(__DIR__ . "/ConfiguracoesController.php");

$controller = new ConfiguracoesController();
$controller->ver_cada();

?>
