<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$dados = turnos_ler_post();

if (Turno::duplicado($dados['turno']) !== null) {
    die("Esse turno já está cadastrado.");
}

$novo = new Turno();
$novo->id    = Turno::proximoId(); // id não é auto_increment.
$novo->turno = $dados['turno'];

if (!$novo->save()) {
    error_log("Erro ao inserir turno: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível inserir o registro na tabela turnos.");
}

header("Location: ../exibir/ver_cada.php?id=" . $novo->id);
exit;

?>
