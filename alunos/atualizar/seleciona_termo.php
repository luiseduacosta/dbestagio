<?php

include_once("../../autentica.inc");

// Somente aluno e admin solicitam o termo de compromisso — mesma regra de
// atualiza_termo.php (onde a gravacao tambem e barrada). O aluno vai direto
// para o proprio registro; o admin abre o formulario pelo DRE.
if (!$isAluno && !$isAdmin) {
	die("Acesso restrito: apenas alunos e administradores podem solicitar o termo de compromisso.");
}

$smarty = new Smarty_estagio;

$smarty->display("alunos-atualizar_seleciona_termo.tpl");

exit;

?>