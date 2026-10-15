<?php
/**
 * mural-excluir_aluno.php — Front-controller da remoção de um aluno da lista
 * de alunos que buscam estágio (inscrições do período atual). Delega toda a
 * lógica ao MuralestagiosController.
 */
require_once(__DIR__ . "/MuralestagiosController.php");

$controller = new MuralestagiosController();
$controller->excluir_aluno();
