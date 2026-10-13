<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php"); // Inicializa os modelos

// Only aluno and admin
if ($isAluno) {
    // Get the registro of the logged-in student using Aluno model
    $alunoObj = Aluno::find($usuario_aluno_id);
    if (!$alunoObj) {
        die("Não foi possível encontrar seu registro");
    }
    $registro = $alunoObj->registro;
// A entrada ao formulario para o admin eh atraves do numero de registro
} elseif ($isAdmin) {
    $registro = isset($_REQUEST['registro']) ? $_REQUEST['registro'] : NULL;
    if (strlen($registro) < 8 or strlen($registro) > 9) {
        die("Quantidade de carateres digitados inválido");
    }
} else {
    die("Acesso negado");
}

// O formulario eh enviado
$submit = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : NULL;
// Tipo de inscricao do aluno: 0 = novo, 1 = cadastrado periodo atual, 2 = nao cadastrado periodo atual
$aluno = isset($_REQUEST['aluno']) ? (int)$_REQUEST['aluno'] : NULL;

$aluno_id        = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : NULL;
$nome            = isset($_REQUEST['nome']) ? trim($_REQUEST['nome']) : '';
$codigo_telefone = isset($_REQUEST['codigo_telefone']) ? (int)$_REQUEST['codigo_telefone'] : 21;
$telefone        = isset($_REQUEST['telefone']) ? trim($_REQUEST['telefone']) : '';
$codigo_celular  = isset($_REQUEST['codigo_celular']) ? (int)$_REQUEST['codigo_celular'] : 21;
$celular         = isset($_REQUEST['celular']) ? trim($_REQUEST['celular']) : '';
$email           = isset($_REQUEST['email']) ? trim($_REQUEST['email']) : '';
$identidade      = isset($_REQUEST['identidade']) ? trim($_REQUEST['identidade']) : '';
$orgao           = isset($_REQUEST['orgao']) ? trim($_REQUEST['orgao']) : '';
$cpf             = isset($_REQUEST['cpf']) ? trim($_REQUEST['cpf']) : '';
$nascimento      = isset($_REQUEST['nascimento']) ? trim($_REQUEST['nascimento']) : '';
$endereco        = isset($_REQUEST['endereco']) ? trim($_REQUEST['endereco']) : '';
$cep             = isset($_REQUEST['cep']) ? trim($_REQUEST['cep']) : '';
$bairro          = isset($_REQUEST['bairro']) ? trim($_REQUEST['bairro']) : '';
$municipio       = isset($_REQUEST['municipio']) ? trim($_REQUEST['municipio']) : '';
$observacoes     = isset($_REQUEST['observacoes']) ? trim($_REQUEST['observacoes']) : '';
$nivel           = isset($_REQUEST['nivel']) ? (int)$_REQUEST['nivel'] : 1;

// Instituicao na qual o aluno estava estagiando no periodo anterior
$instituicao_id_periodo_atual    = isset($_REQUEST['id_instituicao_periodo_atual']) ? (int)$_REQUEST['id_instituicao_periodo_atual'] : (isset($_REQUEST['instituicao_id_periodo_atual']) ? (int)$_REQUEST['instituicao_id_periodo_atual'] : 0);
$instituicao_id_periodo_anterior = isset($_REQUEST['id_instituicao_periodo_anterior']) ? (int)$_REQUEST['id_instituicao_periodo_anterior'] : (isset($_REQUEST['instituicao_id_periodo_anterior']) ? (int)$_REQUEST['instituicao_id_periodo_anterior'] : 0);

$instituicao_id = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : NULL;
$supervisor_id  = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : NULL;

// O formulario eh enviado pelo aluno
if ($submit) {
    if (!ctype_digit((string)$registro)) {
        die("O DRE deve conter apenas carateres numéricos");
    }
    // Verifica o campo instituicao_id
    if (empty($instituicao_id)) {
        die("É obrigatório selecionar a instituição para o qual está solicitando o termo de compromisso");
    }

    /* Classifica a mudança de estágio */
    if ($nivel == 1) {
        echo "Aluno novo<br>";
        $classificacao = 0;
    } else {
        $nivel_inicial = $nivel - 1;
        // 0 = nao muda, 1 = muda
        $muda_estagio = ($instituicao_id == $instituicao_id_periodo_anterior) ? 0 : 1;
        echo "Aluno estagiário.<br>";
        if (($nivel_inicial == 1 || $nivel_inicial == 3) && ($muda_estagio == 1)) {
            $classificacao = 0; // Mudanca fora de regimento
            echo "Mudança de estágio fora do regimento.<br>";
        } elseif (($nivel_inicial == 2) && ($muda_estagio == 1)) {
            $classificacao = 1; // Mudanca regimental
            echo "Mudança de estágio regimental.<br>";
        } elseif (($nivel_inicial == 2) && ($muda_estagio == 0)) {
            $classificacao = 2; // Poderia mudar mas permance no mesmo estagio
            echo "Permanece no mesmo estágio.<br>";
        } else {
            $classificacao = 3; // Permance no mesmo estagio: renovacao
            echo "Permanece no mesmo estágio: renovacao.<br>";
        }
    }

    // Normaliza data de nascimento
    $data_nascimento = '';
    if (!empty($nascimento)) {
        $ts = strtotime(str_replace('/', '-', $nascimento));
        $data_nascimento = ($ts !== false) ? date("Y-m-d", $ts) : $nascimento;
    }

    $niveis_romanos = array(1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV');
    $nivel_romano = isset($niveis_romanos[$nivel]) ? $niveis_romanos[$nivel] : 'I';

    // Model Instituicao
    $instituicaoObj = Instituicao::find($instituicao_id);
    if (!$instituicaoObj) die("Instituição não encontrada");
    $instituicao = $instituicaoObj->instituicao;

    // Model Supervisor
    $supervisorObj = Supervisor::find($supervisor_id);
    if (!$supervisorObj) die("Supervisor não encontrado");
    $supervisor = $supervisorObj->nome;
    $cress      = $supervisorObj->cress;

    $professor_id = null;
    $turno = '';
    // Busco primeiramente a informacao na tabela mural_estagios
    $mural = Mural::where("instituicao_id = ? AND periodo = ?", array($instituicao_id, TC_PERIODO_ATUAL));
    if (empty($mural)) {
        echo "Campo de estágio não abriu seleção neste período<br>";
        // Calculo em base ao professor que trabalhou com essa instituicao
        $ultimosEstagiarios = Estagiario::where("instituicao_id = ? ORDER BY periodo DESC", array($instituicao_id));
        if (empty($ultimosEstagiarios)) {
            die("Não foi possível encontrar um professor associado a esta instituição");
        }
        $professor_id = $ultimosEstagiarios[0]->professor_id;
    } else {
        $muralObj = $mural[0];
        $professor_id = $muralObj->professor_id;
        $turno = $muralObj->horario;
    }

    $data = date("d-m-Y");

    // Inserir / Atualizar conforme tipo do aluno
    if ($aluno === 0) {
        // Aluno novo
        $alunoExistente = Aluno::firstWhere('registro', $registro);
        if ($alunoExistente) {
            $aluno_id = $alunoExistente->id;
            $nome = $alunoExistente->nome;
        } else {
            echo "Insere aluno e estágio. <br>";
            $novoAluno = new Aluno();
            $novoAluno->id              = Aluno::proximoId();
            $novoAluno->registro        = (int)$registro;
            $novoAluno->nome            = $nome;
            $novoAluno->codigo_telefone = $codigo_telefone;
            $novoAluno->telefone        = $telefone;
            $novoAluno->codigo_celular  = $codigo_celular;
            $novoAluno->celular         = $celular;
            $novoAluno->email           = $email;
            $novoAluno->cpf             = $cpf;
            $novoAluno->identidade      = $identidade;
            $novoAluno->orgao           = $orgao;
            $novoAluno->nascimento      = $data_nascimento ?: null;
            $novoAluno->endereco        = $endereco;
            $novoAluno->cep             = $cep;
            $novoAluno->bairro          = $bairro;
            $novoAluno->municipio       = $municipio;
            if (!$novoAluno->save()) {
                die("0 Não foi possível inserir o registro na tabela alunos");
            }
            $aluno_id = $novoAluno->id;
        }

        // Inserir estagiario usando modelo Estagiario
        $novoEstagiario = new Estagiario();
        $novoEstagiario->preencher(array(
            'aluno_id'       => $aluno_id,
            'registro'       => $registro,
            'nivel'          => $nivel,
            'tc'             => 0,
            'tc_solicitacao' => date("Y-m-d"),
            'instituicao_id' => $instituicao_id,
            'supervisor_id'  => $supervisor_id,
            'professor_id'   => $professor_id,
            'periodo'        => TC_PERIODO_ATUAL
        ));
        if (!$novoEstagiario->save()) {
            die("Não foi possível inserir o registro na tabela estagiarios");
        }
    } elseif ($aluno === 1) {
        // Atualiza alunos e estagiarios usando modelos
        $alunoCadastrado = Aluno::firstWhere('registro', $registro);
        if (!$alunoCadastrado) {
            die("Não foi possível consultar a tabela alunos");
        }
        $aluno_id = $alunoCadastrado->id;
        if (!empty($nome)) $alunoCadastrado->nome = $nome; else $nome = $alunoCadastrado->nome;

        echo "Atualiza aluno e estágio. <br>";
        $alunoCadastrado->codigo_telefone = $codigo_telefone;
        $alunoCadastrado->telefone        = $telefone;
        $alunoCadastrado->codigo_celular  = $codigo_celular;
        $alunoCadastrado->celular         = $celular;
        $alunoCadastrado->email           = $email;
        $alunoCadastrado->identidade      = $identidade;
        $alunoCadastrado->orgao           = $orgao;
        $alunoCadastrado->cpf             = $cpf;
        if (!empty($data_nascimento)) $alunoCadastrado->nascimento = $data_nascimento;
        $alunoCadastrado->endereco        = $endereco;
        $alunoCadastrado->cep             = $cep;
        $alunoCadastrado->bairro          = $bairro;
        $alunoCadastrado->municipio       = $municipio;
        $alunoCadastrado->observacoes     = $observacoes;
        $alunoCadastrado->save();

        // Atualiza estagiário existente no período atual
        $estagiariosExistentes = Estagiario::where("aluno_id = ? AND periodo = ?", array($aluno_id, TC_PERIODO_ATUAL));
        if (empty($estagiariosExistentes)) {
            $estagiariosExistentes = Estagiario::where("registro = ? AND periodo = ?", array($registro, TC_PERIODO_ATUAL));
        }
        if (!empty($estagiariosExistentes)) {
            $estagiarioExistente = $estagiariosExistentes[0];
            $estagiarioExistente->preencher(array(
                'nivel'          => $nivel,
                'tc'             => 0,
                'tc_solicitacao' => date("Y-m-d"),
                'instituicao_id' => $instituicao_id,
                'supervisor_id'  => $supervisor_id,
                'professor_id'   => $professor_id
            ));
            if (!$estagiarioExistente->save()) {
                die("1 Não foi possível atualizar o registro na tabela estagiarios");
            }
        }
    } elseif ($aluno === 2) {
        // Atualiza aluno e insere estagiario usando modelos
        $alunoCadastrado = Aluno::firstWhere('registro', $registro);
        if (!$alunoCadastrado) {
            die("Não foi possível consultar a tabela alunos");
        }
        $aluno_id = $alunoCadastrado->id;
        if (!empty($nome)) $alunoCadastrado->nome = $nome; else $nome = $alunoCadastrado->nome;

        echo "Atualiza aluno e insere estágio. <br>";
        $alunoCadastrado->codigo_telefone = $codigo_telefone;
        $alunoCadastrado->telefone        = $telefone;
        $alunoCadastrado->codigo_celular  = $codigo_celular;
        $alunoCadastrado->celular         = $celular;
        $alunoCadastrado->email           = $email;
        $alunoCadastrado->identidade      = $identidade;
        $alunoCadastrado->orgao           = $orgao;
        $alunoCadastrado->cpf             = $cpf;
        if (!empty($data_nascimento)) $alunoCadastrado->nascimento = $data_nascimento;
        $alunoCadastrado->endereco        = $endereco;
        $alunoCadastrado->cep             = $cep;
        $alunoCadastrado->bairro          = $bairro;
        $alunoCadastrado->municipio       = $municipio;
        $alunoCadastrado->observacoes     = $observacoes;
        $alunoCadastrado->save();

        // Insere novo registro de estagiário
        $novoEstagiario = new Estagiario();
        $novoEstagiario->preencher(array(
            'aluno_id'       => $aluno_id,
            'registro'       => $registro,
            'nivel'          => $nivel,
            'tc'             => 0,
            'tc_solicitacao' => date("Y-m-d"),
            'instituicao_id' => $instituicao_id,
            'supervisor_id'  => $supervisor_id,
            'professor_id'   => $professor_id,
            'periodo'        => TC_PERIODO_ATUAL
        ));
        if (!$novoEstagiario->save()) {
            die("Não foi possível inserir o registro na tabela estagiarios");
        }
    }

    echo "<meta http-equiv='refresh' content='0;url=../../imprimir/termo.php?registro=$registro&nome=" . urlencode($nome) . "&nivel_romano=$nivel_romano&instituicao=" . urlencode($instituicao) . "&supervisor=" . urlencode($supervisor) . "&cress=" . urlencode($cress) . "&classificacao=$classificacao'>";
    exit;
}

// Com o registro pesquiso o aluno usando o modelo Aluno
$alunoObj = Aluno::firstWhere('registro', $registro);
if (!$alunoObj) {
    echo "Aluno não cadastrado em estágio. <br />";
    echo "Causa provável: aluno não participou de seleção de estágio. <br />";
    die("Favor enviar um e-mail para <a href='mailto:estagio@ess.ufrj.br'>estagio@ess.ufrj.br</a> informando o seu DRE para verificar a causa do problema.");
}

$aluno_id        = $alunoObj->id;
$nome            = $alunoObj->nome;
$codigo_telefone = $alunoObj->codigo_telefone;
$telefone        = $alunoObj->telefone;
$codigo_celular  = $alunoObj->codigo_celular;
$celular         = $alunoObj->celular;
$email           = $alunoObj->email;
$cpf             = $alunoObj->cpf;
$identidade      = $alunoObj->identidade;
$orgao           = $alunoObj->orgao;
$nascimento      = $alunoObj->nascimento;
$data_sql        = (!empty($nascimento) && $nascimento !== '0000-00-00') ? date("d-m-Y", strtotime($nascimento)) : '';

$endereco        = $alunoObj->endereco;
$cep             = $alunoObj->cep;
$bairro          = $alunoObj->bairro;
$municipio       = $alunoObj->municipio;
$observacoes     = $alunoObj->observacoes;

// Busca estágios recentes do aluno usando o modelo Estagiario
$estagiarios = Estagiario::estagiosPorRegistroDesc($registro);

$instituicao_id_periodo_atual    = 0;
$instituicao_id_periodo_anterior = 0;
$instituicao_id                  = 0;
$instituicao                     = '';
$supervisor_id                   = 0;
$supervisor                      = '';
$nivel                           = 1;
$nivel_formulario                = 1;

if (empty($estagiarios)) {
    // Aluno novo: nunca estagiou
    $aluno            = 0;
    $nivel            = 1;
    $nivel_formulario = 1;
} else {
    $i = 1;
    foreach ($estagiarios as $estagio) {
        $periodo = $estagio['periodo'];
        if ($periodo == TC_PERIODO_ATUAL) {
            // Aluno estagiário já cadastrado no período atual
            $aluno = 1;
            $instituicao_id_periodo_atual = (int)$estagio['instituicao_id'];
            $instituicao_periodo_atual    = $estagio['instituicao'];

            $supervisor_id_periodo_atual  = (int)$estagio['supervisor_id'];
            $supervisor_periodo_atual     = $estagio['supervisor_nome'];

            $nivel_periodo_atual          = (int)$estagio['nivel'];
            $periodo_atual                = $estagio['periodo'];

            // Para o formulário
            $instituicao_id = $instituicao_id_periodo_atual;
            $instituicao    = $instituicao_periodo_atual;
            $supervisor_id  = $supervisor_id_periodo_atual;
            $supervisor     = $supervisor_periodo_atual;

            if ($i == 1) {
                $nivel_formulario = $nivel_periodo_atual;
                $nivel            = $nivel_periodo_atual;
            }
        } else {
            // Aluno estagiário não cadastrado no período atual
            $aluno = ($aluno == 1) ? 1 : 2;
            $instituicao_id_periodo_anterior = (int)$estagio['instituicao_id'];
            $instituicao_periodo_anterior    = $estagio['instituicao'];

            $supervisor_id_periodo_anterior  = (int)$estagio['supervisor_id'];
            $supervisor_periodo_anterior     = $estagio['supervisor_nome'];

            $nivel_periodo_anterior          = (int)$estagio['nivel'];
            $periodo_anterior                = $estagio['periodo'];

            // Para o formulário
            $instituicao_id = $instituicao_id_periodo_anterior;
            $instituicao    = $instituicao_periodo_anterior;
            $supervisor_id  = $supervisor_id_periodo_anterior;
            $supervisor     = $supervisor_periodo_anterior;

            if ($i == 1) {
                $nivel_formulario = $nivel_periodo_anterior;
                if ($nivel_periodo_anterior == 4) {
                    $nivel = $nivel_periodo_anterior;
                } else {
                    $nivel = $nivel_periodo_anterior + 1;
                }
            }
        }

        $i++;
        if ($i > 2) {
            break;
        }
    }
}

$niveis_romanos = array(1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV');
$nivel_romano   = isset($niveis_romanos[$nivel_formulario]) ? $niveis_romanos[$nivel_formulario] : 'I';

// Captura as instituições ordenadas usando modelo Instituicao
$todasInstituicoes = Instituicao::all("ORDER BY instituicao");
$instituicoes = array();
foreach ($todasInstituicoes as $inst) {
    $instituicoes[] = array(
        'id'             => (int)$inst->id,
        'instituicao_id' => (int)$inst->id,
        'instituicao'    => $inst->instituicao
    );
}

// Captura os supervisores ordenados usando modelo Supervisor
$todosSupervisores = Supervisor::all("ORDER BY nome");
$supervisores = array();
foreach ($todosSupervisores as $sup) {
    $supervisores[] = array(
        'id'            => (int)$sup->id,
        'supervisor_id' => (int)$sup->id,
        'nome'          => $sup->nome
    );
}

$smarty = new Smarty_estagio;
// Dados do Aluno
$smarty->assign("aluno_id", $aluno_id);
$smarty->assign("registro", $registro);
$smarty->assign("aluno_nome", $nome);
$smarty->assign("codigo_telefone", $codigo_telefone);
$smarty->assign("telefone", $telefone);
$smarty->assign("codigo_celular", $codigo_celular);
$smarty->assign("celular", $celular);
$smarty->assign("email", $email);
$smarty->assign("cpf", $cpf);
$smarty->assign("identidade", $identidade);
$smarty->assign("orgao", $orgao);
$smarty->assign("nascimento", $data_sql);
$smarty->assign("endereco", $endereco);
$smarty->assign("cep", $cep);
$smarty->assign("bairro", $bairro);
$smarty->assign("municipio", $municipio);
$smarty->assign("observacoes", $observacoes ?? '');

$smarty->assign("instituicao_atual_id", $instituicao_id_periodo_atual);
$smarty->assign("id_instituicao_atual", $instituicao_id_periodo_atual);
$smarty->assign("id_instituicao_anterior", $instituicao_id_periodo_anterior);
$smarty->assign("instituicao_id", $instituicao_id);
$smarty->assign("instituicao", $instituicao ?? '');
$smarty->assign("supervisor_id", $supervisor_id);
$smarty->assign("supervisor", $supervisor ?? '');

$smarty->assign("nivel", $nivel); // próximo nível de estágio
$smarty->assign("nivel_romano", $nivel_romano); // nível de estágio em números romanos
$smarty->assign("aluno", $aluno); // 0 = novo, 1 = cadastrado período atual, 2 = não cadastrado período atual
// Dados das Instituições
$smarty->assign("instituicoes", $instituicoes);
// Dados dos Supervisores
$smarty->assign("supervisores", $supervisores);

$smarty->display("alunos-atualizar_atualiza_termo.tpl");

exit;

?>