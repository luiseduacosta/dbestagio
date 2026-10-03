<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

// Formulário de edição do supervisor (envia para atualiza.php).
$supervisor_id = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
if ($supervisor_id <= 0) {
    die("Supervisor inválido.");
}

$sup = Supervisor::find($supervisor_id);
if ($sup === null) {
    die("Supervisor não encontrado (id $supervisor_id).");
}

$smarty = new Smarty_estagio;

$smarty->assign("supervisor_id", $supervisor_id);
$smarty->assign("nome", $sup->nome);
$smarty->assign("cress", $sup->cress);
$smarty->assign("cpf", $sup->cpf);
$smarty->assign("email", $sup->email);
$smarty->assign("codigo_telefone", $sup->codigo_telefone);
$smarty->assign("telefone", $sup->telefone);
$smarty->assign("codigo_celular", $sup->codigo_celular);
$smarty->assign("celular", $sup->celular);
$smarty->assign("endereco", $sup->endereco);
$smarty->assign("bairro", $sup->bairro);
$smarty->assign("municipio", $sup->municipio);
$smarty->assign("cep", $sup->cep);
$smarty->assign("escola", $sup->escola);
$smarty->assign("ano_formacao", $sup->ano_formacao);
$smarty->assign("cargo", $sup->cargo);
$smarty->assign("regiao", $sup->regiao);
$smarty->assign("observacoes", $sup->observacoes);

// Instituições vinculadas (exibição).
$smarty->assign("instituicao", $sup->instituicoes());

$smarty->display("supervisor_modifica.tpl");

exit;

?>
