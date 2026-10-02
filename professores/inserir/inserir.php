<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$dados = professor_ler_post();
$dados['id'] = 0;

$dup = Professor::duplicado($dados['email'], $dados['cpf'], $dados['siape'], null);
if ($dup !== null) {
    die("Já existe um professor cadastrado com este e-mail, CPF ou SIAPE (id $dup).");
}

$professor = new Professor();
$professor->preencher($dados);
$professor->created  = date('Y-m-d H:i:s');
$professor->modified = date('Y-m-d H:i:s');

if (!$professor->save()) {
    error_log("Erro ao inserir professor: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível inserir o registro na tabela professores.");
}

$id = $professor->getKey();
header("Location: ../exibir/ver_cada.php?professor_id=$id");
exit;

?>