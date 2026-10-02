<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$dados = users_validar_post(true); // senha obrigatória na inserção

// Impede e-mail duplicado.
$existente = User::firstWhere('email', $dados['email']);
if ($existente !== null) {
    die("Já existe um usuário com o e-mail {$dados['email']}.");
}

$novo = new User();
$novo->email         = $dados['email'];
$novo->nome          = $dados['nome'];
$novo->role          = $dados['role'];
$novo->categoria     = $dados['categoria'];
$novo->identificacao = $dados['identificacao'];
$novo->ativo         = $dados['ativo'];
$novo->setPassword($dados['password']);

if (!$novo->save()) {
    error_log("Erro ao inserir usuario: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível inserir o registro na tabela users.");
}

header("Location: ../exibir/listar.php");
exit;

?>