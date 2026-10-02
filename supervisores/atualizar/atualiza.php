<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$supervisor_id = (int)(isset($_POST['supervisor_id']) ? $_POST['supervisor_id'] : 0);

$supervisor = Supervisor::find($supervisor_id);
if ($supervisor === null) {
    die("Supervisor não encontrado (id $supervisor_id).");
}

// Campos editáveis (fluxo simples). Demais campos mantêm-se intactos.
$dados = array();
$dados['nome']      = isset($_POST['nome'])      ? trim($_POST['nome']) : '';
$dados['email']     = isset($_POST['email'])     ? trim($_POST['email']) : null;
$dados['cress']     = isset($_POST['cress'])     ? trim($_POST['cress']) : null;

if ($dados['nome'] === '') {
    die("O campo Nome é obrigatório.");
}
if (mb_strlen($dados['nome'], 'UTF-8') > 70) {
    die("O nome do supervisor excede 70 caracteres.");
}

$supervisor->nome  = $dados['nome'];
$supervisor->email = $dados['email'];
$supervisor->cress = $dados['cress'];
if (!$supervisor->save()) {
    error_log("Erro ao atualizar supervisores: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível atualizar a tabela supervisores. Tente novamente.");
}

header("Location: ../exibir/ver_cada.php?supervisor_id=$supervisor_id");
exit;

?>