<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$usuario = User::find($id);

if ($usuario === null) {
    die("Usuário não encontrado (id $id).");
}

$dados = users_validar_post(false); // na edição a senha é opcional

// Se o e-mail mudou, evita duplicidade com outro usuário.
if ($dados['email'] !== $usuario->email) {
    $existente = User::firstWhere('email', $dados['email']);
    if ($existente !== null && $existente->getKey() != $id) {
        die("Já existe um usuário com o e-mail {$dados['email']}.");
    }
}

$usuario->email         = $dados['email'];
$usuario->nome          = $dados['nome'];
$usuario->role          = $dados['role'];
$usuario->categoria     = $dados['categoria'];
$usuario->identificacao = $dados['identificacao'];
$usuario->ativo         = $dados['ativo'];

// Só altera a senha se o campo foi preenchido.
if ($dados['password'] !== '') {
    $usuario->setPassword($dados['password']);
}

if (!$usuario->save()) {
    error_log("Erro ao atualizar usuario: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível atualizar o registro na tabela users.");
}

header("Location: ../exibir/listar.php");
exit;

?>