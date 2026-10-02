<?php

include_once(__DIR__ . "/../autentica.inc");
require_once(__DIR__ . "/../libphp/models.php");

$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;

// Instituição da oferta (via modelo, consulta parametrizada).
$oferta = Mural::find($muralestagio_id);
$instituicao = ($oferta !== null) ? $oferta->instituicao : '';

// Lista de inscritos da oferta no período atual.
// O JOIN com alunos/mural_estagios é feito na própria consulta (sem N+1).
$lista = Inscricao::listar(PERIODO_ATUAL, $muralestagio_id);

// Mapeia a saída do modelo para o formato esperado pelo template listaInscritos.tpl.
// Mantém apenas inscrições com aluno cadastrado (mesmo comportamento do código
// anterior, que só listava registros existentes na tabela alunos).
$inscritos = array();
foreach ($lista as $ins) {
    if (empty($ins['aluno_nome'])) {
        continue;
    }
    $inscritos[] = array(
        'id'        => $ins['id'],
        'registro'  => $ins['registro'],
        'nome'      => $ins['aluno_nome'],
        'email'     => $ins['aluno_email'],
        'telefone'  => $ins['aluno_telefone'],
        'celular'   => $ins['aluno_celular'],
        'data'      => $ins['data'],
        'aluno'     => ($ins['aluno_id'] > 0) ? 1 : 0,
    );
}

// Mantém a ordenação original (por data de inscrição).
if (!empty($inscritos)) {
    usort($inscritos, function ($a, $b) {
        return strcmp($a['data'], $b['data']);
    });
}

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica", $sistema_autentica);
$smarty->assign("muralestagio_id", $muralestagio_id);
$smarty->assign("id_instituicao", $muralestagio_id);
$smarty->assign("instituicao", $instituicao);
$smarty->assign("inscritos", $inscritos);
$smarty->display("../../mural/listaInscritos.tpl");

?>