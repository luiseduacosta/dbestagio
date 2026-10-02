<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$dadosVisita = visitas_validar_post();

$nova = new Visita();
foreach ($dadosVisita as $campo => $valor) {
    $nova->$campo = $valor;
}

if (!$nova->save()) {
    error_log("Erro ao inserir visita: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível inserir o registro na tabela visitas.");
}

header("Location: ../exibir/listar.php");
exit;

?>