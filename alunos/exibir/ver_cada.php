<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;
$registro = isset($_REQUEST['registro']) ? (int)$_REQUEST['registro'] : 0;
$periodo  = isset($_REQUEST['periodo']) ? trim($_REQUEST['periodo']) : '';
$origem   = isset($_REQUEST['origem']) ? trim($_REQUEST['origem']) : '';
$periodo_atual = isset($_REQUEST['periodo_atual']) ? trim($_REQUEST['periodo_atual']) : PERIODO_ATUAL;
if ($periodo_atual === '') {
    $periodo_atual = PERIODO_ATUAL;
}

// Se veio por registro, converto para aluno_id.
if ($aluno_id <= 0 && $registro > 0) {
    $db = Aluno::$db;
    $res = $db->Execute("SELECT id FROM alunos WHERE registro = ? LIMIT 1", array($registro));
    if ($res !== false && !$res->EOF) {
        $aluno_id = (int)$res->fields['id'];
    }
}

$dados = ($aluno_id > 0) ? Aluno::buscar($aluno_id) : null;
if ($dados === null) {
    die("Aluno não encontrado (id " . ($aluno_id ?: $registro) . ").");
}
$aluno_id = (int)$dados['id'];

// Histórico de estágios do aluno (uma única consulta com JOINs).
$historico_estagio = Aluno::historicoEstagios($aluno_id);

// Tempo de curso cursado (mesma fórmula do fluxo anterior).
$tempo_cursado = '';
if (!empty($periodo_atual) && !empty($dados['ingresso'])) {
    $tempo0 = explode("-", $dados['ingresso']);
    if (count($tempo0) >= 2) {
        $tempo_inicial   = (int)$tempo0[0];
        $periodo_inicial = (int)$tempo0[1];
        $tempo1 = explode("-", $periodo_atual);
        if (count($tempo1) >= 2) {
            $tempo_final   = (int)$tempo1[0];
            $periodo_final = (int)$tempo1[1];
            $tempo_cursado = ($tempo_final - $tempo_inicial);
            if ($periodo_inicial < $periodo_final) {
                $tempo_cursado = ($tempo_cursado * 2) + 2;
            } elseif ($periodo_inicial > $periodo_final) {
                $tempo_cursado = ($tempo_cursado * 2);
            } elseif ($periodo_inicial === $periodo_final) {
                $tempo_cursado = ($tempo_cursado * 2) + 1;
            }
        }
    }
}

// Períodos de turmas (filtro do template).
$periodos = Aluno::periodos();

$smarty = new Smarty_estagio;
$smarty->assign("logado", $isAdmin);
$smarty->assign("isAdmin", $isAdmin);
$smarty->assign("periodo", $periodo);
$smarty->assign("origem", $origem);
$smarty->assign("indice", 0);
$smarty->assign("aluno_id", $aluno_id);
$smarty->assign("instituicao_id", $dados['instituicao_id'] ?? 0);
$smarty->assign("supervisor_id", $dados['supervisor_id'] ?? 0);
$smarty->assign("registro", $dados['registro']);
$smarty->assign("nome", $dados['nome']);
$smarty->assign("codigo_telefone", $dados['codigo_telefone']);
$smarty->assign("telefone", $dados['telefone']);
$smarty->assign("codigo_celular", $dados['codigo_celular']);
$smarty->assign("celular", $dados['celular']);
$smarty->assign("email", strtolower((string)$dados['email']));
$smarty->assign("cpf", $dados['cpf']);
$smarty->assign("identidade", $dados['identidade']);
$smarty->assign("orgao", $dados['orgao']);
$smarty->assign("nascimento", $dados['nascimento']);
$smarty->assign("endereco", $dados['endereco']);
$smarty->assign("cep", $dados['cep']);
$smarty->assign("bairro", $dados['bairro']);
$smarty->assign("municipio", $dados['municipio']);
$smarty->assign("observacoes", $dados['observacoes']);
$smarty->assign("periodo_intro", $dados['ingresso']);
$smarty->assign("tempo_cursado", $tempo_cursado);
$smarty->assign("historico_estagio", $historico_estagio);
$smarty->assign("periodos", $periodos);

$smarty->display("alunos-exibir_ver_cada.tpl");

exit;

?>