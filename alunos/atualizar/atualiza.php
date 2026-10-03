<?php

include_once(__DIR__ . "/../../autentica.inc");
include_once(__DIR__ . "/../../libphp/models.php");

$origem = isset($_REQUEST['origem']) ? trim($_REQUEST['origem']) : '';
if (substr_count($origem, "seleciona.php") == 1) {
    $origem = $_SERVER['PHP_SELF'];
}
if ($origem === '') {
    $origem = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
}

// ------------------------- Processa o POST -------------------------
if (($_SERVER['REQUEST_METHOD'] === 'POST') || (isset($_GET['acao']) && $_GET['acao'] == 1)) {

    $aluno_id    = (int)isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;
    $registro    = isset($_REQUEST['registro']) ? trim($_REQUEST['registro']) : '';
    $nome        = isset($_REQUEST['nome']) ? trim($_REQUEST['nome']) : '';
    $observacoes = isset($_REQUEST['observacoes']) ? trim($_REQUEST['observacoes']) : '';

    // Atualização de um estágio específico (fluxo ataliza_estagio).
    $estagiario_id = isset($_POST['estagiario_id']) ? (int)$_POST['estagiario_id'] : 0;
    $periodo = isset($_POST['periodo']) ? trim($_POST['periodo']) : '';
    $nivel   = isset($_POST['nivel']) ? trim($_POST['nivel']) : '';
    $tc      = isset($_POST['tc']) ? trim($_POST['tc']) : '';
    $instituicao_id = isset($_POST['instituicao_id']) ? (int)$_POST['instituicao_id'] : 0;
    $supervisor_id  = isset($_POST['supervisor_id']) ? (int)$_POST['supervisor_id'] : 0;
    $professor_id   = isset($_POST['professor_id']) ? (int)$_POST['professor_id'] : 0;
    $nota = isset($_POST['nota']) ? trim($_POST['nota']) : '';
    $ch   = isset($_POST['ch']) ? trim($_POST['ch']) : '';

    if ($estagiario_id > 0) {
        // Atualiza somente a tabela estagiarios.
        $ok = Aluno::$db->Execute(
            "UPDATE estagiarios SET aluno_id=?, registro=?, nivel=?, periodo=?, tc=?, "
            . "supervisor_id=?, instituicao_id=?, professor_id=?, nota=?, ch=? WHERE id=?",
            array($aluno_id, $registro, $nivel, $periodo, $tc, $supervisor_id,
                  $instituicao_id, $professor_id, $nota, $ch, $estagiario_id)
        );
        if ($ok === false) {
            die("Nao foi possivel atualizar o registro na tabela estagiarios");
        }
    } else {
        // Atualiza o aluno (e sincroniza o registro em estagiarios).
        $aluno = Aluno::find($aluno_id);
        if ($aluno === null) {
            die("Aluno não encontrado (id $aluno_id).");
        }
        $aluno->registro   = $registro;
        $aluno->nome       = $nome;
        $aluno->telefone   = isset($_REQUEST['telefone']) ? trim($_REQUEST['telefone']) : '';
        $aluno->celular    = isset($_REQUEST['celular']) ? trim($_REQUEST['celular']) : '';
        $aluno->email      = strtolower(trim(isset($_REQUEST['email']) ? $_REQUEST['email'] : ''));
        $aluno->cpf        = isset($_REQUEST['cpf']) ? trim($_REQUEST['cpf']) : '';
        $aluno->identidade = isset($_REQUEST['identidade']) ? trim($_REQUEST['identidade']) : '';
        $aluno->orgao      = isset($_REQUEST['orgao']) ? trim($_REQUEST['orgao']) : '';
        $aluno->endereco   = isset($_REQUEST['endereco']) ? trim($_REQUEST['endereco']) : '';
        $aluno->cep        = isset($_REQUEST['cep']) ? trim($_REQUEST['cep']) : '';
        $aluno->bairro     = isset($_REQUEST['bairro']) ? trim($_REQUEST['bairro']) : '';
        $aluno->municipio  = isset($_REQUEST['municipio']) ? trim($_REQUEST['municipio']) : '';
        $aluno->observacoes = $observacoes;
        if (isset($_REQUEST['codigo_telefone'])) $aluno->codigo_telefone = (int)$_REQUEST['codigo_telefone'];
        if (isset($_REQUEST['codigo_celular']))  $aluno->codigo_celular  = (int)$_REQUEST['codigo_celular'];
        if (!empty($_REQUEST['nascimento'])) {
            $data_nascimento = '';
            $nasc = trim($_REQUEST['nascimento']);
            if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $nasc, $m)) {
                $data_nascimento = $m[3] . "-" . $m[2] . "-" . $m[1];
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $nasc)) {
                $data_nascimento = $nasc;
            }
            if ($data_nascimento !== '') $aluno->nascimento = $data_nascimento;
        }

        // Turno do aluno (alunos.turno_id -> turnos.turno).
        if (isset($_REQUEST['turno_id'])) {
            $turno_id = (int)$_REQUEST['turno_id'];
            if ($turno_id > 0 && Turno::find($turno_id) === null) {
                die("Turno inválido (id $turno_id).");
            }
            $aluno->turno_id = ($turno_id > 0) ? $turno_id : null;
        }

        // Verifica duplicidade de registro (salvo neste próprio aluno).
        if (Aluno::registroDuplicado($registro, $aluno_id) !== null) {
            die("Já existe outro aluno com o registro $registro.");
        }

        if (!$aluno->save()) {
            die("Nao foi possivel atualizar o registro na tabela alunos");
        }

        // Sincroniza o campo registro na tabela estagiarios.
        $ok2 = Aluno::$db->Execute("UPDATE estagiarios SET registro=? WHERE aluno_id=?", array($registro, $aluno_id));
        if ($ok2 === false) {
            die("Nao foi possivel atualizar o campo registro na tabela estagiarios");
        }
    }

    // Vai para listar.php ou volta ao ver_cada, conforme a origem.
    if (substr_count($origem, "listar.php") == 1) {
        header("Location:" . $origem);
    } else {
        header("Location: ../exibir/ver_cada.php?aluno_id=$aluno_id");
    }
    exit;
}

// ------------------------- Exibe o formulário -------------------------
$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;
$dados = Aluno::buscar($aluno_id);
if ($dados === null) {
    die("Aluno não encontrado (id $aluno_id).");
}

// Formata a data de nascimento para dd/mm/aaaa.
$data_sql = '';
if (!empty($dados['nascimento']) && $dados['nascimento'] !== '0000-00-00') {
    $ts = strtotime($dados['nascimento']);
    $data_sql = ($ts === false) ? $dados['nascimento'] : date('d/m/Y', $ts);
}

$registro  = $dados['registro'];
$nome      = $dados['nome'];

// Estagiários do aluno (histórico) — uma consulta com JOINs.
$estagiarios = Aluno::historicoEstagios($aluno_id);
// O template espera a chave 'id' como identificador do estagiário.
foreach ($estagiarios as $k => $e) {
    $estagiarios[$k]['id'] = $e['estagiario_id'];
}

// Selects do formulário.
$instituicoes = array();
$res = Aluno::$db->Execute("SELECT id, instituicao FROM instituicoes ORDER BY instituicao");
if ($res) {
    while (!$res->EOF) {
        $instituicoes[] = array('instituicao_id' => (int)$res->fields['id'], 'instituicao' => $res->fields['instituicao']);
        $res->MoveNext();
    }
}
$supervisores = array();
$res = Aluno::$db->Execute("SELECT id, nome FROM supervisores ORDER BY nome");
if ($res) {
    while (!$res->EOF) {
        $supervisores[] = array('supervisor_id' => (int)$res->fields['id'], 'supervisor' => $res->fields['nome']);
        $res->MoveNext();
    }
}
$professores = array();
$res = Aluno::$db->Execute("SELECT id, nome FROM professores ORDER BY nome");
if ($res) {
    while (!$res->EOF) {
        $professores[] = array('professor_id' => (int)$res->fields['id'], 'professor' => $res->fields['nome']);
        $res->MoveNext();
    }
}

$smarty = new Smarty_estagio;
$smarty->assign("origem", $origem);
$smarty->assign("aluno_id", $aluno_id);
$smarty->assign("registro", $registro);
$smarty->assign("aluno_nome", $nome);
$smarty->assign("codigo_telefone", $dados['codigo_telefone']);
$smarty->assign("telefone", $dados['telefone']);
$smarty->assign("codigo_celular", $dados['codigo_celular']);
$smarty->assign("celular", $dados['celular']);
$smarty->assign("email", strtolower((string)$dados['email']));
$smarty->assign("cpf", $dados['cpf']);
$smarty->assign("identidade", $dados['identidade']);
$smarty->assign("orgao", $dados['orgao']);
$smarty->assign("nascimento", $data_sql);
$smarty->assign("endereco", $dados['endereco']);
$smarty->assign("cep", $dados['cep']);
$smarty->assign("bairro", $dados['bairro']);
$smarty->assign("municipio", $dados['municipio']);
$smarty->assign("observacoes", $dados['observacoes']);
$smarty->assign("turno_id", (int)$dados['turno_id']);
$smarty->assign("turnos", Turno::seleciona());
$smarty->assign("estagiarios", $estagiarios);
$smarty->assign("instituicoes", $instituicoes);
$smarty->assign("supervisores", $supervisores);
$smarty->assign("professores", $professores);
$smarty->display("alunos-atualizar_atualiza.tpl");

exit;

?>