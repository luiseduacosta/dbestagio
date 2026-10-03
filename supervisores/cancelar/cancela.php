<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$supervisor_id = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
$indice        = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;

if ($supervisor_id <= 0) {
    echo "Supervisor inválido (id $supervisor_id).";
    exit;
}

$sup = Supervisor::find($supervisor_id);
if ($sup === null) {
    echo "Supervisor não encontrado (id $supervisor_id).";
    echo "<meta http-equiv='refresh' content='2;url=../exibir/ver_cada.php?indice=$indice' />";
    exit;
}

// ---------- Checagem de registros dependentes ----------
$erros = array();

// Estagiários (alunos supervisionados).
$countEstagiarios = $sup->countEstagiarios();
if ($countEstagiarios > 0) {
    $erros[] = "alunos supervisionados ($countEstagiarios)";
}

if (!empty($erros)) {
    echo "Operação abortada. Não é possível excluir este supervisor porque existem "
       . "registros relacionados:<br>"
       . "<ul><li>" . implode('</li><li>', $erros) . "</li></ul>";
    echo "<meta http-equiv='refresh' content='3;url=../exibir/ver_cada.php?supervisor_id=$supervisor_id' />";
    exit;
}

// ---------- Nenhum registro dependente: exclui ----------
if (!$sup->delete()) {
    error_log("Erro ao excluir supervisor: " . Supervisor::$db->ErrorMsg());
    echo "Não foi possível excluir o registro da tabela supervisores.";
    exit;
}

// Remove também os vínculos com instituições (inst_super).
Supervisor::$db->Execute("DELETE FROM inst_super WHERE supervisor_id = ?", array($supervisor_id));

header("Location: ../exibir/ver_cada.php?indice=$indice");
exit;

?>
