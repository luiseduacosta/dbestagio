<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$turno = Turno::find($id);

if ($turno === null) {
    die("Turno não encontrado (id $id).");
}

$dados = turnos_ler_post();

if (Turno::duplicado($dados['turno'], $id) !== null) {
    die("Já existe outro turno com esse nome.");
}

$turno->turno = $dados['turno'];

if (!$turno->save()) {
    error_log("Erro ao atualizar turno: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível atualizar o registro na tabela turnos.");
}

header("Location: ../exibir/ver_cada.php?id=$id");
exit;

?>
