<?php
/**
 * excluir.php — Front-controller da exclusão de uma oferta do mural.
 *
 * Mantém o endpoint usado pela ficha da oferta (ver_cada) e delega toda a
 * lógica ao MuralestagiosController. Exige login e não permite excluir a
 * oferta enquanto houver alunos inscritos. A lógica original deste arquivo
 * (checagem de inscritos em inscricoes e delete em mural_estagios) agora
 * vive no model Mural (libphp/Mural.php).
 */
require_once(__DIR__ . "/MuralestagiosController.php");

$controller = new MuralestagiosController();
$controller->excluir();
