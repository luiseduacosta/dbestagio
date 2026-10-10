<?php
/**
 * ver_cada.php — Front-controller da ficha de uma oferta do mural.
 *
 * Mantém o endpoint público (ficha registro a registro das ofertas do
 * período atual) e delega toda a lógica ao MuralestagiosController.
 * A lógica original deste arquivo (navegação por posição, perfil do
 * usuário logado via cookie, formatação do registro) agora vive no
 * model Mural (libphp/Mural.php) e no controlador.
 */
require_once(__DIR__ . "/MuralestagiosController.php");

$controller = new MuralestagiosController();
$controller->ver_cada();
