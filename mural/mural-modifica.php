<?php
/**
 * mural-modifica.php — Front-controller do formulário de edição de uma
 * oferta do mural.
 *
 * Mantém o endpoint público e delega toda a lógica ao
 * MuralestagiosController. Como o script original, NÃO exige login
 * (paridade de comportamento; ver nota no controlador).
 */
require_once(__DIR__ . "/MuralestagiosController.php");

$controller = new MuralestagiosController();
$controller->editar();
