<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$usuario = User::find($id);

if ($usuario === null) {
    die("Usuário não encontrado (id $id).");
}

if (!$usuario->delete()) {
    error_log("Erro ao excluir usuario: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível excluir o registro da tabela users.");
}

header("Location: ../exibir/listar.php");
exit;

?>