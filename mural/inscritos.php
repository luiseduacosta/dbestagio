<?php
/**
 * inscritos.php — Tabela única dos alunos inscritos no mural de estágios.
 *
 * Substitui as quatro listagens legadas que exibiam os mesmos dados por
 * recortes diferentes:
 *
 *   lista-alunos.php       -> inscritos.php                (todos os grupos)
 *   lista-novos_alunos.php -> inscritos.php?grupo=novos    (alunos novos)
 *   alunosConhecidos.php   -> inscritos.php?grupo=conhecidos (já estagiaram)
 *   listaInscritos.php     -> inscritos.php?muralestagio_id=ID (uma oferta)
 *
 * Delega toda a lógica ao MuralestagiosController (ação inscritos): a consulta
 * fica em Inscricao::listarAlunosPorPeriodo() e a página em
 * smarty/templates/muralestagios-inscritos.tpl.
 */
require_once(__DIR__ . "/MuralestagiosController.php");

$controller = new MuralestagiosController();
$controller->inscritos();
