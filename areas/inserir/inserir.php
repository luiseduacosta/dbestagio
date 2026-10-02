<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$area = isset($_POST['area']) ? trim($_POST['area']) : '';

if ($area === '') {
    die("O campo Área é obrigatório.");
}
if (mb_strlen($area, 'UTF-8') > 90) {
    die("O nome da área excede 90 caracteres.");
}

// Evita duplicar a mesma área (case-insensitive).
$existe = ADODB_Model::$db->GetOne(
    "SELECT COUNT(*) FROM areas WHERE LOWER(area) = LOWER(?)",
    array($area)
);
if ($existe > 0) {
    die("Essa área já está cadastrada.");
}

$nova = new Area();
$nova->area = $area;
if (!$nova->save()) {
    error_log("Erro ao inserir area: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível inserir o registro na tabela areas.");
}

echo "<p>Registro inserido</p>";
echo "<p><a href='../exibir/listar.php'>Voltar para a listagem de áreas</a></p>";

exit;

?>