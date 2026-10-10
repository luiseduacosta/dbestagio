<?php
/**
 * ver-mural.php — Front-controller da listagem do mural de estágios.
 *
 * Mantém o endpoint público (index do mural) e delega toda a lógica ao
 * MuralestagiosController. A lógica original deste arquivo (consulta das
 * ofertas do período atual, totais e estatísticas de inscritos) agora vive
 * no model Mural (libphp/Mural.php).
 */
require_once(__DIR__ . "/MuralestagiosController.php");

$controller = new MuralestagiosController();
$controller->index();
