<?php
/**
 * mural-atualiza.php — Front-controller da gravação da edição de uma
 * oferta do mural.
 *
 * Mantém o endpoint público (action do formulário mural-modifica.tpl) e
 * delega toda a lógica ao MuralestagiosController. Como o script original,
 * NÃO exige login (paridade de comportamento; ver nota no controlador).
 */
require_once(__DIR__ . "/MuralestagiosController.php");

$controller = new MuralestagiosController();
$controller->atualizar();
