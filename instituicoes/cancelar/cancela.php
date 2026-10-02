<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$instituicao_id = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0;
$indice         = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;

if ($instituicao_id <= 0) {
    echo "Instituição inválida (id $instituicao_id).";
    exit;
}

$inst = Instituicao::find($instituicao_id);
if ($inst === null) {
    echo "Instituição não encontrada (id $instituicao_id).";
    echo "<meta http-equiv='refresh' content='2;../exibir/ver_cada.php?indice=$indice' />";
    exit;
}

// ---------- Checagem de registros dependentes ----------
$erros = array();

// Supervisores vinculados (tabela ponte inst_super).
$countInstSuper = (int)Instituicao::$db->GetOne(
    "SELECT COUNT(*) FROM inst_super WHERE instituicao_id = ?",
    array($instituicao_id)
);
if ($countInstSuper > 0) {
    $erros[] = "supervisores vinculados ($countInstSuper)";
}

// Estagiários (alunos que estagiaram na instituição).
$countEstagiarios = $inst->countEstagiarios();
if ($countEstagiarios > 0) {
    $erros[] = "estagiários ($countEstagiarios)";
}

// Visitas.
$countVisitas = $inst->countVisitas();
if ($countVisitas > 0) {
    $erros[] = "visitas ($countVisitas)";
}

// Mural de estágios.
$countMurais = $inst->countMurais();
if ($countMurais > 0) {
    $erros[] = "registros de mural de estágios ($countMurais)";
}

if (!empty($erros)) {
    echo "Operação abortada. É necessário primeiro excluir os seguintes registros "
       . "relacionados a esta instituição:<br>"
       . "<ul><li>" . implode('</li><li>', $erros) . "</li></ul>";
    echo "<meta http-equiv='refresh' content='3;../exibir/ver_cada.php?instituicao_id=$instituicao_id' />";
    exit;
}

// ---------- Nenhum registro dependente: exclui ----------
if (!$inst->delete()) {
    error_log("Erro ao excluir instituicao: " . Instituicao::$db->ErrorMsg());
    echo "Não foi possível excluir o registro da tabela instituicoes.";
    exit;
}

if (!$indice) $indice = 0;
echo "<meta http-equiv='refresh' content='0;../exibir/ver_cada.php?indice=$indice' />";
exit;

?>