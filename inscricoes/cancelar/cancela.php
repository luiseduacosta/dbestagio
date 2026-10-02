<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$inscricao = Inscricao::find($id);
if ($inscricao === null) {
    die("Inscrição não encontrada (id $id).");
}

if (!$inscricao->delete()) {
    error_log("Erro ao excluir inscricao: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível excluir o registro da tabela inscricoes.");
}

$periodo        = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
$muralestagio_id = isset($_GET['muralestagio_id']) ? (int)$_GET['muralestagio_id'] : 0;

header("Location: ../exibir/listar.php?periodo=" . urlencode($periodo) . "&muralestagio_id=$muralestagio_id");
exit;

?>