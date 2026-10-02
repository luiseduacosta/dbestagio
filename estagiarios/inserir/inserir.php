<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$dados = estagiario_ler_post();
$dados['id'] = 0;

// Evita duplicidade (mesmo aluno + período + nível).
$dup = Estagiario::duplicado($dados['aluno_id'], $dados['periodo'], $dados['nivel'], null);
if ($dup !== null) {
    die("Este aluno já possui um estágio neste período e nível (id $dup).");
}

$estagiario = new Estagiario();
$estagiario->preencher($dados);

if (!$estagiario->save()) {
    error_log("Erro ao inserir estagio: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível inserir o registro na tabela estagiarios.");
}

$id = $estagiario->getKey();
header("Location: ../exibir/ver_cada.php?id=$id");
exit;

?>