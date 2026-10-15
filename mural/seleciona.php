<?php
/**
 * seleciona.php — Front-controller do seletor de ofertas do mural
 * (formulário de modificar/excluir). Delega toda a lógica ao
 * MuralestagiosController. A lógica original deste arquivo (consulta das
 * ofertas do período atual) agora vive no model Mural (libphp/Mural.php).
 */
require_once(__DIR__ . "/MuralestagiosController.php");

$controller = new MuralestagiosController();
$controller->selecionar();
