<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$supervisor_id = isset($_POST['supervisor_id']) ? (int)$_POST['supervisor_id'] : 0;

$supervisor = Supervisor::find($supervisor_id);
if ($supervisor === null) {
    die("Supervisor não encontrado (id $supervisor_id).");
}

// Campos editáveis do cadastro do supervisor.
$dados = array();
$dados['nome']            = isset($_POST['nome'])  ? trim($_POST['nome']) : '';
$dados['cress']           = isset($_POST['cress']) ? trim($_POST['cress']) : null;
$dados['cpf']             = isset($_POST['cpf'])   ? trim($_POST['cpf']) : null;
$dados['email']           = isset($_POST['email']) ? trim($_POST['email']) : null;
$dados['codigo_telefone'] = isset($_POST['codigo_telefone']) && $_POST['codigo_telefone'] !== '' ? (int)$_POST['codigo_telefone'] : null;
$dados['telefone']        = isset($_POST['telefone'])  ? trim($_POST['telefone']) : null;
$dados['codigo_celular']  = isset($_POST['codigo_celular']) && $_POST['codigo_celular'] !== '' ? (int)$_POST['codigo_celular'] : null;
$dados['celular']         = isset($_POST['celular'])   ? trim($_POST['celular']) : null;
$dados['endereco']        = isset($_POST['endereco'])  ? trim($_POST['endereco']) : null;
$dados['bairro']          = isset($_POST['bairro'])    ? trim($_POST['bairro']) : null;
$dados['municipio']       = isset($_POST['municipio']) ? trim($_POST['municipio']) : null;
$dados['cep']             = isset($_POST['cep'])       ? trim($_POST['cep']) : null;
$dados['escola']          = isset($_POST['escola'])    ? trim($_POST['escola']) : null;
$dados['ano_formacao']    = isset($_POST['ano_formacao']) ? trim($_POST['ano_formacao']) : null;
$dados['cargo']           = isset($_POST['cargo'])     ? trim($_POST['cargo']) : null;
$dados['regiao']          = isset($_POST['regiao']) && $_POST['regiao'] !== '' ? (int)$_POST['regiao'] : 7;
$dados['observacoes']     = isset($_POST['observacoes']) ? trim($_POST['observacoes']) : null;

if ($dados['nome'] === '') {
    die("O campo Nome é obrigatório.");
}
if (mb_strlen($dados['nome'], 'UTF-8') > 70) {
    die("O nome do supervisor excede 70 caracteres.");
}

$supervisor->preencher($dados);
if (!$supervisor->save()) {
    error_log("Erro ao atualizar supervisor: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível atualizar a tabela supervisores. Tente novamente.");
}

header("Location: ../exibir/ver_cada.php?supervisor_id=$supervisor_id");
exit;

?>
