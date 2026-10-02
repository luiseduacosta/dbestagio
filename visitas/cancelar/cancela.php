<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

$visita = Visita::find($id);
if ($visita === null) {
    die("Visita não encontrada (id $id).");
}

if (!$visita->delete()) {
    error_log("Erro ao excluir visita: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível excluir o registro da tabela visitas.");
}

header("Location: ../exibir/listar.php");
exit;

?>