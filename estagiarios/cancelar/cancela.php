<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

$est = Estagiario::find($id);
if ($est === null) {
    die("Estágio não encontrado (id $id).");
}

if (!$est->delete()) {
    error_log("Erro ao excluir estagio: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível excluir o registro da tabela estagiarios.");
}

header("Location: ../exibir/listar.php");
exit;

?>