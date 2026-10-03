<?php

include_once(__DIR__ . "/../../autentica.inc");
include_once(__DIR__ . "/../../libphp/models.php");

$estagiario_id = isset($_GET['estagiario_id']) ? $_GET['estagiario_id'] : NULL;
$aluno_id      = isset($_GET['aluno_id']) ? $_GET['aluno_id'] : NULL;

if ($estagiario_id === null) {
    die("Nenhuma estagiário foi informado.");
}

$estagiario = Estagiario::find($estagiario_id);
if ($estagiario === null) {
    die("Estágio não encontrado.");
}
$estagiario->delete();

header("Location:../inserir/acrescentar_estagio.php?aluno_id=$aluno_id");

?>