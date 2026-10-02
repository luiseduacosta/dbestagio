<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

// ----- Exibição do formulário de edição -----
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $est = Estagiario::find($id);
    if ($est === null) {
        die("Estágio não encontrado (id $id).");
    }
    $data = $est->toArray();

    $v = array(
        'aluno_id'         => (int)$data['aluno_id'],
        'periodo'          => $data['periodo'],
        'nivel'            => $data['nivel'],
        'tc'               => (int)$data['tc'],
        'tc_solicitacao'   => ($data['tc_solicitacao'] !== null && $data['tc_solicitacao'] !== '0000-00-00') ? $data['tc_solicitacao'] : '',
        'instituicao_id'   => (int)$data['instituicao_id'],
        'supervisor_id'    => (int)$data['supervisor_id'],
        'professor_id'     => (int)$data['professor_id'],
        'nota'             => ($data['nota'] !== null) ? $data['nota'] : '',
        'ch'               => ($data['ch'] !== null) ? (int)$data['ch'] : '',
        'ajuste2020'       => $data['ajuste2020'],
        'benetransporte'   => (int)$data['benetransporte'],
        'benealimentacao'  => (int)$data['benealimentacao'],
        'benebolsa'        => $data['benebolsa'],
        'observacoes'      => $data['observacoes'],
    );

    $alunos       = Estagiario::alunos();
    $instituicoes = Instituicao::listar_todas();
    $supervisores = Supervisor::listar('nome');
    $professores  = Professor::listar('', '', 'p.nome');
    $niveis       = Estagiario::niveis();

    $smarty = new Smarty_estagio;
    $smarty->assign("alunos", $alunos);
    $smarty->assign("instituicoes", $instituicoes);
    $smarty->assign("supervisores", $supervisores);
    $smarty->assign("professores", $professores);
    $smarty->assign("niveis", $niveis);
    $smarty->assign("v", $v);
    $smarty->assign("acao", "atualiza.php");
    $smarty->assign("titulo", "Editar estágio");
    $smarty->assign("e_edicao", true);
    $smarty->display("estagiarios_form.tpl");
    exit;
}

// ----- Gravação da edição -----
$dados = estagiario_ler_post();

$est = Estagiario::find($id);
if ($est === null) {
    die("Estágio não encontrado (id $id).");
}

$dup = Estagiario::duplicado($dados['aluno_id'], $dados['periodo'], $dados['nivel'], $id);
if ($dup !== null) {
    die("Este aluno já possui outro estágio neste período e nível (id $dup).");
}

$est->preencher($dados);

if (!$est->save()) {
    error_log("Erro ao atualizar estagio: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível atualizar o registro na tabela estagiarios.");
}

header("Location: ../exibir/ver_cada.php?id=$id");
exit;

?>