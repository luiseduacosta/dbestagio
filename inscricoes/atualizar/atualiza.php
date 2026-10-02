<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$inscricao = Inscricao::find($id);
if ($inscricao === null) {
    die("Inscrição não encontrada (id $id).");
}

$dados = inscricao_validar_post();

// Evita duplicidade salvo na própria inscrição em edição.
$dup = Inscricao::duplicada($dados['registro'], $dados['muralestagio_id'], $dados['periodo'], $id);
if ($dup !== null) {
    die("Este aluno já está inscrito nesta oferta ({$dados['periodo']}).");
}

$aluno = Inscricao::alunoPorRegistro($dados['registro']);
if ($aluno === null) {
    die("Nenhum aluno encontrado com o registro {$dados['registro']}.");
}

$inscricao->registro        = $dados['registro'];
$inscricao->muralestagio_id = $dados['muralestagio_id'];
$inscricao->data            = $dados['data'];
$inscricao->periodo         = $dados['periodo'];
$inscricao->aluno_id        = $aluno['id'];

if (!$inscricao->save()) {
    error_log("Erro ao atualizar inscricao: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível atualizar o registro na tabela inscricoes.");
}

header("Location: ../exibir/listar.php?periodo={$dados['periodo']}&muralestagio_id={$dados['muralestagio_id']}");
exit;

?>