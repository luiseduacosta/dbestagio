<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$indice        = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;
$supervisor_id = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
$periodo       = isset($_REQUEST['periodo']) ? trim($_REQUEST['periodo']) : '';
if ($periodo === '0') {
    $periodo = '';
}

// Períodos existentes: alimentam o filtro da página e servem de lista
// fechada de valores aceitos para $periodo (proteção contra SQL injection).
$periodos = Estagiario::periodos();
if ($periodo !== '' && !in_array($periodo, $periodos, true)) {
    $periodo = '';
}

$db = Supervisor::$db;

// Lista ordenada dos supervisores (filtro opcional por período). Base para
// a navegação: Primeiro / Retrocede / Avança / Último.
$sql    = "SELECT DISTINCT s.id, s.nome FROM supervisores AS s";
$params = array();
if ($periodo !== '') {
    $sql .= " INNER JOIN estagiarios AS e ON e.supervisor_id = s.id AND e.periodo = ?";
    $params[] = $periodo;
}
$sql .= " ORDER BY s.nome";

$rs = Supervisor::query($sql, $params);
if (empty($rs)) {
    die("Não foi possível consultar a tabela supervisores.");
}
$lista = array();
foreach ($rs as $item) {
    $lista[] = (int)$item->id;
}
$ultimo = count($lista);

// Posição na lista: com supervisor_id localiza o índice dele; senão usa o
// índice pedido (com wrap-around, como antes).
if ($supervisor_id > 0) {
    $posicao = array_search($supervisor_id, $lista, true);
    if ($posicao !== false) {
        $indice = (int)$posicao;
    }
} elseif ($ultimo > 0) {
    if ($indice >= $ultimo) $indice = 0;
    if ($indice < 0)        $indice = $ultimo - 1;
    $supervisor_id = $lista[$indice];
}

$supervisor = ($supervisor_id > 0) ? Supervisor::find($supervisor_id) : null;
if ($supervisor === null) {
    die($supervisor_id > 0
        ? "Supervisor não encontrado (id $supervisor_id)."
        : "Nenhum supervisor cadastrado.");
}

// Acrescenta uma instituição ao supervisor (formulário da própria página).
if (!empty($_POST['num_instituicao'])) {
    $num_instituicao = (int)$_POST['num_instituicao'];
    if ($num_instituicao > 0) {
        $ok = Instituicao::query(
            "INSERT INTO inst_super (supervisor_id, instituicao_id) VALUES (?, ?)",
            array($supervisor_id, $num_instituicao)
        );
        if ($ok === false) {
            die("Não foi possível inserir dados na tabela inst_super");
        }
    }
}

// Instituições vinculadas ao supervisor (campo de trabalho).
$emprego = $supervisor->instituicoes();

// Alunos supervisionados (consulta única, sem N+1).
$alunos = array();
$rs = Estagiario::query(
    "SELECT a.id AS aluno_id, a.registro, a.nome, e.periodo, e.instituicao_id, i.instituicao
     FROM estagiarios AS e
     INNER JOIN alunos AS a ON a.id = e.aluno_id
     LEFT JOIN instituicoes AS i ON i.id = e.instituicao_id
     WHERE e.supervisor_id = ?
     ORDER BY e.periodo, a.nome",
    array($supervisor_id)
);
if ($rs === false) {
    die("Não foi possível consultar a tabela alunos");
}
foreach ($rs as $item) {
    $alunos[] = array(
        'aluno_id'       => (int)$item->aluno_id,
        'registro'       => (int)$item->registro,
        'nome'           => $item->nome,
        'periodo'        => $item->periodo,
        'instituicao_id' => (int)$item->instituicao_id,
        'instituicao'    => ($item->instituicao !== null) ? $item->instituicao : 'Sem dados',
    );
}

// Vínculo do supervisor com o curso (mesma regra usada no índice).
$id_curso = null;
$cress = (string)$supervisor->cress;
if ($cress !== '' && $cress !== '0' && ctype_digit($cress)) {
    $curso = Supervisor::query("SELECT id FROM curso_inscricao_supervisor WHERE cress = ? LIMIT 1", array($cress));
    if (!empty($curso)) {
        $id_curso = (int)$curso[0]->id;
    }
}

// Instituições para o formulário de vínculo.
$instituicoes = Instituicao::listar_todas();

$smarty = new Smarty_estagio;
$smarty->assign("ultimo", ($ultimo > 0) ? $ultimo - 1 : 0);
$smarty->assign("indice", $indice);
$smarty->assign("supervisor_id", $supervisor_id);
$smarty->assign("periodo", $periodo);
$smarty->assign("cress", $supervisor->cress);
$smarty->assign("nome", $supervisor->nome);
$smarty->assign("cpf", $supervisor->cpf);
$smarty->assign("codigo_telefone", $supervisor->codigo_telefone);
$smarty->assign("telefone", $supervisor->telefone);
$smarty->assign("codigo_celular", $supervisor->codigo_celular);
$smarty->assign("celular", $supervisor->celular);
$smarty->assign("email", $supervisor->email);
$smarty->assign("endereco", $supervisor->endereco);
$smarty->assign("bairro", $supervisor->bairro);
$smarty->assign("municipio", $supervisor->municipio);
$smarty->assign("cep", $supervisor->cep);
$smarty->assign("escola", $supervisor->escola);
$smarty->assign("ano_formacao", $supervisor->ano_formacao);
$smarty->assign("cargo", $supervisor->cargo);
$smarty->assign("regiao", $supervisor->regiao);
$smarty->assign("observacoes", $supervisor->observacoes);
$smarty->assign("id_curso", $id_curso);
$smarty->assign("emprego", $emprego);
$smarty->assign("alunos", $alunos);
$smarty->assign("periodos", $periodos);
$smarty->assign("instituicoes", $instituicoes);
$smarty->display("supervisores_ver_cada.tpl");

exit;

?>