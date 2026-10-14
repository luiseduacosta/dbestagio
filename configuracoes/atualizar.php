<?php
/**
 * configuracoes/atualizar.php — Edita ou salva as configurações do sistema.
 */
require_once(__DIR__ . "/ConfiguracoesController.php");

$controller = new ConfiguracoesController();
$controller->atualizar();

?>
