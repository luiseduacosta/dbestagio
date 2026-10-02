<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$cress           = isset($_POST['cress']) ? trim($_POST['cress']) : '';
$nome            = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$email           = isset($_POST['email']) ? trim($_POST['email']) : '';
$instituicao_id  = (int)(isset($_POST['instituicao_id']) ? $_POST['instituicao_id'] : 0);
$supervisor_id   = (int)(isset($_POST['supervisor_id']) ? $_POST['supervisor_id'] : 0);

if ($instituicao_id <= 0) {
    die("Instituição inválida (id $instituicao_id).");
}

// Se não foi informado supervisor, cria um novo supervisor e usa o id dele.
if ($supervisor_id == 0) {
    ADODB_Model::$db->Execute(
        "INSERT INTO supervisores (cress, nome, email) VALUES (?, ?, ?)",
        array($cress, $nome, $email)
    );
    $supervisor_id = (int)ADODB_Model::$db->Insert_ID();
    if ($supervisor_id <= 0) {
        error_log("Erro ao inserir supervisor: " . ADODB_Model::$db->ErrorMsg());
        die("Não foi possível inserir dados na tabela supervisores. Tente novamente.");
    }
}

// Vincula o supervisor à instituição.
$inst = Instituicao::find($instituicao_id);
if ($inst === null) {
    die("Instituição não encontrada (id $instituicao_id).");
}
$inst->vincularSupervisor($supervisor_id);

header("Location: ../exibir/ver_cada.php?instituicao_id=$instituicao_id");
exit;

?>