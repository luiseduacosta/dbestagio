<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$id_area = isset($_REQUEST["id_area"]) ? (int)$_REQUEST["id_area"] : 0;

$area_obj = Area::find($id_area);
if ($area_obj === null) {
    echo "Área não encontrada (id $id_area).";
    exit;
}

// Bloqueia a exclusão caso existam instituições vinculadas.
$quantidade = $area_obj->countInstituicoes();
if ($quantidade > 0) {
    echo "<p>Operação abortada porque $quantidade instituições dependem desta área.</p>";
    echo "<p><a href='../exibir/instituicoes.php?id_area=$id_area'>Ver as instituições desta área</a></p>";
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