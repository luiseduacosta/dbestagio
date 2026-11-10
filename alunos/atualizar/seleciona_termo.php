<?php

include_once("../../autentica.inc");

// Somente aluno e admin solicitam o termo de compromisso — mesma regra de
// atualiza_termo.php (onde a gravacao tambem e barrada).
if (!$isAluno && !$isAdmin) {
	die("Acesso restrito: apenas alunos e administradores podem solicitar o termo de compromisso.");
}

// O aluno nao digita DRE: o proprio atualiza_termo.php ja descarta o valor
// enviado e usa o registro da sessao (Aluno::find($usuario_aluno_id)). Como
// o campo seria ignorado, pula a selecao e vai direto para o formulario.
// O admin, que pode pesquisar qualquer DRE, continua passando pela selecao.
if ($isAluno) {
	header("Location: atualiza_termo.php");
	exit;
}

$smarty = new Smarty_estagio;

$smarty->display("alunos-atualizar_seleciona_termo.tpl");

exit;

?>
