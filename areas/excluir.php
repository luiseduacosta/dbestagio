<?php
/**
 * areas/excluir.php — Front-controller da exclusão de área. Verifica
 * dependências (instituições vinculadas) e, se não houver, apaga o registro.
 * Delega toda a lógica ao AreasController.
 */
require_once(__DIR__ . "/AreasController.php");

$controller = new AreasController();
$controller->excluir();
