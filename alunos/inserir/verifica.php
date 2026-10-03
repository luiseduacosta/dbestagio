<?php

if (empty($origem)) {
    $origem = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
}

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$registro = isset($_REQUEST['registro']) ? trim($_REQUEST['registro']) : '';
$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;

// Busca o aluno na tabela `alunos` (única fonte cadastral).
if ($registro !== '') {
    $dados = Aluno::$db->Execute(
        "SELECT id, registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, "
        . "identidade, orgao, nascimento, endereco, cep, bairro, municipio "
        . "FROM alunos WHERE registro = ? LIMIT 1",
        array((int)$registro)
    );
} else {
    $dados = Aluno::$db->Execute(
        "SELECT id, registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, "
        . "identidade, orgao, nascimento, endereco, cep, bairro, municipio "
        . "FROM alunos WHERE id = ? LIMIT 1",
        array($aluno_id)
    );
}
if ($dados === false) {
    die("Não foi possível consultar a tabela alunos");
}

// Aluno já cadastrado: mostra a ficha do aluno.
if (!$dados->EOF) {
    header("Location:../exibir/ver_cada.php?registro=" . (int)$dados->fields['registro']);
    exit;
}

// Aluno não cadastrado: exibe o formulário para cadastrar um novo aluno.
$smarty = new Smarty_estagio;
$smarty->assign("origem", $origem);
$smarty->assign("aluno_id", $aluno_id);
$smarty->assign("registro", $registro);
$smarty->assign("nome", '');
$smarty->assign("ingresso", '');
$smarty->assign("codigo_telefone", 21);
$smarty->assign("telefone", '');
$smarty->assign("codigo_celular", 21);
$smarty->assign("celular", '');
$smarty->assign("email", '');
$smarty->assign("cpf", '');
$smarty->assign("identidade", '');
$smarty->assign("orgao", '');
$smarty->assign("nascimento", '');
$smarty->assign("endereco", '');
$smarty->assign("cep", '');
$smarty->assign("bairro", '');
$smarty->assign("municipio", '');
$smarty->assign("cadastro", 0);
$smarty->assign("turnos", Turno::seleciona());
$smarty->assign("turno_id", 0);
$smarty->display("alunos-inserir_verifica.tpl");

exit;

?>