<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$professor_id = isset($_REQUEST['professor_id']) ? (int)$_REQUEST['professor_id'] : 0;

$prof = Professor::find($professor_id);
if ($prof === null) {
    die("Professor não encontrado (id $professor_id).");
}

// Proteção: não exclui professor que possui estagiários vinculados.
$num_estagios = Professor::buscar($professor_id);
$num_estagios = $num_estagios ? (int)$num_estagios['num_estagios'] : 0;

if ($num_estagios > 0) {
    die("Não é possível excluir este professor: há {$num_estagios} estágio(s) vinculado(s) a ele. "
        . "Remova os vínculos nos estagiários antes de excluir.");
}

if (!$prof->delete()) {
    error_log("Erro ao excluir professor: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível excluir o registro da tabela professores.");
}

header("Location: ../exibir/listar.php");
exit;

?>