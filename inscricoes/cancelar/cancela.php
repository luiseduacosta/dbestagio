<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

// Permissões: admin (tudo) ou aluno (apenas a própria inscrição).
inscricao_exigir_permissao();

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$inscricao = Inscricao::find($id);
if ($inscricao === null) {
    die("Inscrição não encontrada (id $id).");
}
if ($isAluno) {
    $ins_arr = Inscricao::buscar($id);
    if ($ins_arr === null || !inscricao_e_do_aluno($ins_arr)) {
        die("Esta inscrição não pertence ao seu usuário.");
    }
}

if (!$inscricao->delete()) {
    error_log("Erro ao excluir inscricao: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível excluir o registro da tabela inscricoes.");
}

$periodo        = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
$muralestagio_id = isset($_GET['muralestagio_id']) ? (int)$_GET['muralestagio_id'] : 0;

header("Location: ../exibir/listar.php?periodo=" . urlencode($periodo) . "&muralestagio_id=$muralestagio_id");
exit;

?>