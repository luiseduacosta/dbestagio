<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

// Permissões: admin (tudo) ou aluno (apenas a própria inscrição).
inscricao_exigir_permissao();

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

$ins = Inscricao::buscar($id);
if ($ins === null) {
    die("Inscrição não encontrada (id $id).");
}
if ($isAluno && !inscricao_e_do_aluno($ins)) {
    die("Esta inscrição não pertence ao seu usuário.");
}

$smarty = new Smarty_estagio;
$smarty->assign("ins", $ins);
$smarty->display("inscricoes_ver_cada.tpl");

exit;

?>