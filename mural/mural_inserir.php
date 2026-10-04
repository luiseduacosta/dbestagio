<?php
/**
 * mural_inserir.php — Front-controller do formulário de inserção de estágio.
 *
 * Mantém o endpoint público (usado nos links do menu e no action do formulário
 * de mural_inserir.tpl) e delega toda a lógica ao MuralestagiosController.
 */
require_once(__DIR__ . "/MuralestagiosController.php");

$controller = new MuralestagiosController();
$controller->inserir();