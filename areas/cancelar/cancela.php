<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$area_id = isset($_REQUEST["area_id"]) ? (int)$_REQUEST["area_id"] : 0;

$area_obj = Area::find($area_id);
if ($area_obj === null) {
    echo "Área não encontrada (id $area_id).";
    exit;
}

// Bloqueia a exclusão caso existam instituições vinculadas.
$quantidade = $area_obj->countInstituicoes();
if ($quantidade > 0) {
    echo "<p>Operação abortada porque $quantidade instituições dependem desta área.</p>";
    echo "<p><a href='../exibir/instituicoes.php?area_id=$area_id'>Ver as instituições desta área</a></p>";
    exit;
}

if (!$area_obj->delete()) {
    error_log("Erro ao excluir area: " . ADODB_Model::$db->ErrorMsg());
    echo "Não foi possível excluir o registro da tabela areas.";
    exit;
}

echo "Registro excluído<br>";
echo "<p><a href='../exibir/listar.php'>Voltar para a listagem de áreas</a></p>";

exit;

?>