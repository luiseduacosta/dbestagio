<?php
/**
 * mural-aluno_inscricao.php — Front-controller do cadastro público do candidato novo.
 *
 * Mantém a rota pública (POST de muralestagios-alunos_insere.tpl, alcançada
 * por verifica.php) e delega toda a lógica ao MuralestagiosController.
 */
require_once(__DIR__ . "/MuralestagiosController.php");

$controller = new MuralestagiosController();
$controller->inscricao_aluno();
