<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

// Permissões: admin (tudo) ou aluno (apenas para si mesmo).
inscricao_exigir_permissao();
if ($isAluno) {
    $_POST['registro'] = inscricao_aluno_registro_logado();
}

$dados = inscricao_validar_post();

// Impede inscrição duplicada do mesmo aluno na mesma oferta/período.
if (Inscricao::duplicada($dados['registro'], $dados['muralestagio_id'], $dados['periodo']) !== null) {
    die("Este aluno já está inscrito nesta oferta ({$dados['periodo']}).");
}

// Confirma que o aluno existe.
$aluno = Inscricao::alunoPorRegistro($dados['registro']);
if ($aluno === null) {
    die("Nenhum aluno encontrado com o registro {$dados['registro']}.");
}

$nova = new Inscricao();
$nova->registro        = $dados['registro'];
$nova->muralestagio_id = $dados['muralestagio_id'];
$nova->data            = $dados['data'];
$nova->periodo         = $dados['periodo'];
$nova->aluno_id        = $aluno['id'];
$nova->timestamp       = date('Y-m-d H:i:s');

if (!$nova->save()) {
    error_log("Erro ao inserir inscricao: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível inserir o registro na tabela inscricoes.");
}

header("Location: ../exibir/listar.php?periodo={$dados['periodo']}&muralestagio_id={$dados['muralestagio_id']}");
exit;

?>