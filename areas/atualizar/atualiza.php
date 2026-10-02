<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$id_area = isset($_POST["id_area"]) ? (int)$_POST["id_area"] : 0;
$area    = isset($_POST["area"]) ? trim($_POST["area"]) : '';

if ($area === '') {
    die("O campo Área é obrigatório.");
}
if (mb_strlen($area, 'UTF-8') > 90) {
    die("O nome da área excede 90 caracteres.");
}

$area_obj = Area::find($id_area);
if ($area_obj === null) {
    die("Área não encontrada (id $id_area).");
}

$area_obj->area = $area;
if (!$area_obj->save()) {
    error_log("Erro ao atualizar area: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível atualizar a tabela areas. Tente novamente.");
}

header("Location: ../exibir/listar.php");
exit;

?>