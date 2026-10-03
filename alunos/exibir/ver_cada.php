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

// Navegação registro a registro (barra superior).
$indice = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;
$botao  = isset($_REQUEST['botao']) ? trim($_REQUEST['botao']) : '';
if ($botao !== '') {
    // Ao usar os botões, ignora o aluno da URL: a seleção passa a ser pelo índice.
    $aluno_id = 0;
    $registro = 0;
}

// Se veio por registro, converto para aluno_id.
if ($aluno_id <= 0 && $registro > 0) {
    $db = Aluno::$db;
    $res = $db->Execute("SELECT id FROM alunos WHERE registro = ? LIMIT 1", array($registro));
    if ($res !== false && !$res->EOF) {
        $aluno_id = (int)$res->fields['id'];
    }
}

// Lista ordenada de alunos (apenas os ids) — base da navegação.
$alunos_ids = array();
$res = Aluno::$db->Execute("SELECT id FROM alunos ORDER BY nome");
if ($res === false) {
    die("Não foi possível consultar a tabela alunos.");
}
while (!$res->EOF) {
    $alunos_ids[] = (int)$res->fields['id'];
    $res->MoveNext();
}
$ultimo = count($alunos_ids);

// Botões Primeiro / -10 / Retroceder / Avançar / +10 / Último (com wrap-around).
switch ($botao) {
    case 'primeiro':
        $indice = 0;
        break;
    case 'menos_10':
        $indice -= 10;
        if ($indice < 0) $indice = $ultimo - 1;
        break;
    case 'retroceder':
        $indice--;
        if ($indice < 0) $indice = $ultimo - 1;
        break;
    case 'avancar':
        $indice++;
        if ($indice >= $ultimo) $indice = 0;
        break;
    case 'mais_10':
        $indice += 10;
        if ($indice >= $ultimo) $indice = 0;
        break;
    case 'ultimo':
        $indice = $ultimo - 1;
        break;
}

if ($ultimo > 0) {
    if ($indice >= $ultimo) $indice = 0;
    if ($indice < 0) $indice = $ultimo - 1;
} else {
    $indice = 0;
}

// Com aluno_id (link direto) posiciona a barra no aluno; sem ele, seleciona pelo índice.
if ($aluno_id > 0) {
    $posicao = array_search($aluno_id, $alunos_ids, true);
    if ($posicao !== false) {
        $indice = (int)$posicao;
    }
} elseif ($ultimo > 0) {
    $aluno_id = $alunos_ids[$indice];
}

$dados = ($aluno_id > 0) ? Aluno::buscar($aluno_id) : null;
if ($dados === null) {
    die($aluno_id > 0
        ? "Aluno não encontrado (id $aluno_id)."
        : "Nenhum aluno cadastrado.");
}
$aluno_id = (int)$dados['id'];

// Histórico de estágios do aluno (uma única consulta com JOINs).
$historico_estagio = Aluno::historicoEstagios($aluno_id);

// Turno do aluno (alunos.turno_id -> turnos.turno).
$turno = '';
if (!empty($dados['turno_id'])) {
    $turno_obj = Turno::find((int)$dados['turno_id']);
    if ($turno_obj !== null) {
        $turno = $turno_obj->turno;
    }
}

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
$smarty->assign("indice", $indice);
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
$smarty->assign("turno", $turno);
$smarty->assign("tempo_cursado", $tempo_cursado);
$smarty->assign("historico_estagio", $historico_estagio);
$smarty->assign("periodos", $periodos);

$smarty->display("alunos-exibir_ver_cada.tpl");

exit;

?>