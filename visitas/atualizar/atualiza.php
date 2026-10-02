<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$id = (int)(isset($_POST['id']) ? $_POST['id'] : 0);
$visita = Visita::find($id);

if ($visita === null) {
    die("Visita não encontrada (id $id).");
}

$dadosVisita = visitas_validar_post();
foreach ($dadosVisita as $campo => $valor) {
    $visita->$campo = $valor;
}

if (!$visita->save()) {
    error_log("Erro ao atualizar visita: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível atualizar o registro na tabela visitas.");
}

header("Location: ../exibir/listar.php");
exit;

?>