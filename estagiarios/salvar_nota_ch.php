<?php
/**
 * estagiarios/salvar_nota_ch.php — Front-controller do lançamento de nota e
 * carga horária (POST). Delega a lógica ao EstagiariosController, que valida
 * a permissão (admin ou o professor responsável pelo estágio).
 */
require_once(__DIR__ . "/EstagiariosController.php");

$controller = new EstagiariosController();
$controller->salvarNotaCh();
