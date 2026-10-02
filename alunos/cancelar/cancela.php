<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;
$registro = isset($_REQUEST['registro']) ? (int)$_REQUEST['registro'] : 0;

if ($aluno_id <= 0 && $registro > 0) {
    $res = Aluno::$db->Execute("SELECT id FROM alunos WHERE registro = ? LIMIT 1", array($registro));
    if ($res !== false && !$res->EOF) {
        $aluno_id = (int)$res->fields['id'];
    }
}

if ($aluno_id <= 0) {
    exit;
}

// Só permite excluir o aluno se ele não tiver estágios vinculados.
$res = Aluno::$db->Execute("SELECT COUNT(*) AS qtd FROM estagiarios WHERE aluno_id = ?", array($aluno_id));
$quantidade = ($res !== false) ? (int)$res->fields['qtd'] : 0;

if ($quantidade === 0) {
    $aluno = Aluno::find($aluno_id);
    if ($aluno !== null) {
        if (!$aluno->delete()) {
            error_log("Erro ao excluir aluno: " . ADODB_Model::$db->ErrorMsg());
            die("Não foi possível excluir o registro do aluno.");
        }
    }
    header("Location:../exibir/listar.php");
} else {
    header("Location:ver_cancela.php?aluno_id=$aluno_id&erro=0");
}

exit;

?>