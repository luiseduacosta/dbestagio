<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php"); // Inicializa os modelos

// Only aluno and admin
if ($isAluno) {
    // Get the registro of the logged-in student using Aluno model
    $aluno = Aluno::find($usuario_aluno_id);
    if (!$aluno) {
        die("Não foi possível encontrar seu registro");
    }
    $registro = $aluno->registro;
// A entrada ao formulario para o admin eh atraves do numero de registro
} elseif ($isAdmin) {
    $registro = isset($_REQUEST['registro']) ? $_REQUEST['registro'] : NULL;
    // echo "Registro: " . $registro;
    if (strlen($registro) < 8 or strlen($registro) > 9) {
        die("Quantidade de carateres digitados inválido");
    }
} else {
    die("Acesso negado");
}

// O formulario eh enviado
$submit = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : NULL;
// Tipo de inscricao do aluno: 0 = novo, 1 = cadastrado periodo atual, 2 = nao cadastrado periodo atual
$aluno = isset($_REQUEST['aluno']) ? $_REQUEST['aluno'] : NULL;

$aluno_id        = isset($_REQUEST['aluno_id']) ? $_REQUEST['aluno_id'] :  NULL;
$nome            = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '';
$codigo_telefone = isset($_REQUEST['codigo_telefone']) ? $_REQUEST['codigo_telefone'] : '';
$telefone        = isset($_REQUEST['telefone']) ? $_REQUEST['telefone'] : '';
$codigo_celular  = isset($_REQUEST['codigo_celular']) ? $_REQUEST['codigo_celular'] : '';
$celular         = isset($_REQUEST['celular']) ? $_REQUEST['celular'] : '';
$email           = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$identidade      = isset($_REQUEST['identidade']) ? $_REQUEST['identidade'] : '';
$orgao           = isset($_REQUEST['orgao']) ? $_REQUEST['orgao'] : '';
$cpf             = isset($_REQUEST['cpf']) ? $_REQUEST['cpf'] : '';
$nascimento      = isset($_REQUEST['nascimento']) ? $_REQUEST['nascimento'] : '';
$endereco        = isset($_REQUEST['endereco']) ? $_REQUEST['endereco'] : '';
$cep             = isset($_REQUEST['cep']) ? $_REQUEST['cep'] : '';
$bairro          = isset($_REQUEST['bairro']) ? $_REQUEST['bairro'] : '';
$municipio       = isset($_REQUEST['municipio']) ? $_REQUEST['municipio'] : '';
$observacoes     = isset($_REQUEST['observacoes']) ? $_REQUEST['observacoes'] : '';
$nivel           = isset($_REQUEST['nivel']) ? $_REQUEST['nivel'] : 1;
// Instituicao na qual o aluno estava estagiando no periodo anterior
$instituicao_id_periodo_atual = isset($_REQUEST['instituicao_id_periodo_atual']) ? $_REQUEST['instituicao_id_periodo_atual'] : '0';
$instituicao_id_periodo_anterior = isset($_REQUEST['instituicao_id_periodo_anterior']) ? $_REQUEST['instituicao_id_periodo_anterior'] : '0';

$instituicao_id = isset($_REQUEST['instituicao_id']) ? $_REQUEST['instituicao_id'] : NULL;
$supervisor_id = isset($_REQUEST['supervisor_id']) ? $_REQUEST['supervisor_id'] : NULL;

// O formulario eh enviado pelo aluno
if ($submit) {
    if (!ctype_digit($registro)) {
        die("O DRE deve conter apenas carateres numéricos");
    }
    // Verifica o campo instituicao_id
    if (empty($instituicao_id))
        die("É obrigatório selecionar a instituição para o qual está solicitando o termo de compromisso");

    // echo "Tipo de aluno: " . $aluno . "<br>";
    // echo "Nivel: ". $nivel . " Estagio solicitado: " . $estagio_id . " Estagio anterior: " . $instituicao_id_periodo_anterior;
    // echo " Estagio atual: " . $instituicao_id_periodo_atual . "<br>";

    /* Classifica a mudança de estágio */
    if ($nivel == 1) {
        echo "Aluno novo<br>";
    } else {
        $nivel_inicial = $nivel - 1;
        // 0 = nao muda, 1 = muda
        $muda_estagio = ($instituicao_id == $instituicao_id_periodo_anterior) ? 0 : 1;
        // echo "Muda estagio: " . $muda_estagio . "<br>";
        echo "Aluno estagiário.<br>";
        if (($nivel_inicial == 1 or $nivel_inicial == 3) and ($muda_estagio == 1)) { // ($id_instituicao != $id_instituicao_periodo_anterior)) {
            $classificacao = 0; // Mudanca fora de regimento
            // echo "Classificacao " . $classificacao . "<br>";
            echo "Mudança de estágio fora do regimento.";
            echo "<br>";
        } elseif (($nivel_inicial == 2) and ($muda_estagio == 1)) { // ($id_instituicao != $id_instituicao_periodo_anterior)) {
            $classificacao = 1; // Mudanca regimental
            // echo "Classificacao " . $classificacao . "<br>";
            echo "Mudança de estágio regimental.";
            echo "<br>";
        } elseif (($nivel_inicial == 2) and ($muda_estagio == 0)) {
            $classificacao = 2; // Poderia mudar mas permance no mesmo estagio
            // echo "Classificacao " . $classificacao . "<br>";
            echo "Permanece no mesmo estágio.<br>";
        } else {
            $classificacao = 3; // Permance no mesmo estagio
            echo "Permanece no mesmo estágio: renovacao.<br>";
        }
    }
    // die;
    /*
     * Prepara a informacao a ser utilizada
     */
    // Para salvar tenho que utilizar o formato aaaa/mm/dd/
    $data_nascimento = date("Y-m-d", strtotime($nascimento));

    switch ($nivel) {
        case 1:
            $nivel_romano = 'I';
            break;
        case 2:
            $nivel_romano = 'II';
            break;
        case 3:
            $nivel_romano = 'III';
            break;
        case 4:
            $nivel_romano = 'IV';
            break;
    }

    // Use models to fetch related data
    $instituicaoObj = Instituicao::find($instituicao_id);
    if (!$instituicaoObj) die("Instituição não encontrada");
    $instituicao = $instituicaoObj->instituicao;
    
    $supervisorObj = Supervisor::find($supervisor_id);
    if (!$supervisorObj) die("Supervisor não encontrado");
    $supervisor = $supervisorObj->nome;
    $cress = $supervisorObj->cress;
    
    $professor_id = null;
    $turno = '';
    // Busco primeiramente a informacao na tabela mural_estagio
    $mural = Mural::firstWhere('id', $instituicao_id);
    if (!$mural || $mural->periodo != TC_PERIODO_ATUAL) {
        echo "Campo de estágio não abriu seleção neste período<br>";
        // Calculo em base ao professor que trabalhou com essa instituicao
        $ultimosEstagiarios = Estagiario::where('instituicao_id = ?', array($instituicao_id));
        if (empty($ultimosEstagiarios)) {
            die("Não foi possível encontrar um professor associado a esta instituição");
        }
        $professor_id = $ultimosEstagiarios[0]->professor_id;
    } else {
        $professor_id = $mural->professor_id;
        $turno = $mural->horario;
    } // Agora ja estou com a informacao para ser inserida na tabela estagiarios

    $data = date("d-m-Y");

    // echo "Aluno estagiario tipo: " . $aluno . "<br>";
    // Inserir aluno e estagiario
    if ($aluno == 0) {
        // Inserir aluno - Usando modelo Aluno
        $alunoExistente = Aluno::firstWhere('registro', $registro);
        if (!$alunoExistente) {
            die("Não foi possivel consultar a tabela alunos");
        }

        $nome = $alunoExistente->nome;
        $codigo_telefone = $alunoExistente->codigo_telefone;
        $telefone = $alunoExistente->telefone;
        $codigo_celular = $alunoExistente->codigo_celular;
        $celular = $alunoExistente->celular;
        $email = $alunoExistente->email;
        $cpf = $alunoExistente->cpf;
        $identidade = $alunoExistente->identidade;
        $orgao = $alunoExistente->orgao;
        $data_nascimento = $alunoExistente->nascimento;
        $endereco = $alunoExistente->endereco;
        $cep = $alunoExistente->cep;
        $bairro = $alunoExistente->bairro;
        $municipio = $alunoExistente->municipio;

        echo "Insere aluno e estágio. <br>";
        // Cria novo registro de aluno (lógica original mantida)
        $novoAluno = new Aluno();
        $novoAluno->registro = $registro;
        $novoAluno->nome = $nome;
        $novoAluno->codigo_telefone = $codigo_telefone;
        $novoAluno->telefone = $telefone;
        $novoAluno->codigo_celular = $codigo_celular;
        $novoAluno->celular = $celular;
        $novoAluno->email = $email;
        $novoAluno->cpf = $cpf;
        $novoAluno->identidade = $identidade;
        $novoAluno->orgao = $orgao;
        $novoAluno->nascimento = $data_nascimento;
        $novoAluno->endereco = $endereco;
        $novoAluno->cep = $cep;
        $novoAluno->bairro = $bairro;
        $novoAluno->municipio = $municipio;
        
        if (!$novoAluno->save()) {
            die("0 Não foi possível inserir o registro na tabela alunos");
        }
        $aluno_id = $db->Insert_ID();

        // Inserir estagiario usando modelo Estagiario
        $novoEstagiario = new Estagiario();
        $novoEstagiario->preencher(array(
            'aluno_id' => $aluno_id,
            'registro' => $registro,
            'nivel' => $nivel,
            'tc' => '0',
            'tc_solicitacao' => date("Y-m-d"),
            'instituicao_id' => $instituicao_id,
            'supervisor_id' => $supervisor_id,
            'professor_id' => $professor_id,
            'periodo' => TC_PERIODO_ATUAL
        ));
        
        if (!$novoEstagiario->save()) {
            die("Não foi possível inserir o registro na tabela estagiarios");
        }
        // die;
        // Atualizar alunos e estagiario
    } elseif ($aluno == 1) {
        // Atualiza alunos e estagiarios usando modelos
        $alunoCadastrado = Aluno::firstWhere('registro', $registro);
        if (!$alunoCadastrado) {
            die("Não foi possivel consultar a tabela alunos");
        }
        $nome = $alunoCadastrado->nome;

        echo "Atualiza aluno e estágio. <br>";
        // Atualiza dados do aluno
        $alunoCadastrado->codigo_telefone = $codigo_telefone;
        $alunoCadastrado->telefone = $telefone;
        $alunoCadastrado->codigo_celular = $codigo_celular;
        $alunoCadastrado->celular = $celular;
        $alunoCadastrado->email = $email;
        $alunoCadastrado->identidade = $identidade;
        $alunoCadastrado->orgao = $orgao;
        $alunoCadastrado->cpf = $cpf;
        $alunoCadastrado->nascimento = $data_nascimento;
        $alunoCadastrado->endereco = $endereco;
        $alunoCadastrado->cep = $cep;
        $alunoCadastrado->bairro = $bairro;
        $alunoCadastrado->municipio = $municipio;
        $alunoCadastrado->observacoes = $observacoes;
        $alunoCadastrado->save();

        // Atualiza estagiário existente
        $estagiarioExistente = Estagiario::firstWhere('aluno_id = ? AND periodo = ?', array($aluno_id, TC_PERIODO_ATUAL));
        if ($estagiarioExistente) {
            $estagiarioExistente->preencher(array(
                'nivel' => $nivel,
                'tc' => '0',
                'tc_solicitacao' => date("Y-m-d"),
                'instituicao_id' => $instituicao_id,
                'supervisor_id' => $supervisor_id,
                'professor_id' => $professor_id
            ));
            if (!$estagiarioExistente->save()) {
                die("1 Não foi possível atualizar o registro na tabela estagiarios");
            }
        }
        // echo $sql_estagiarios . "<br>";
        // Atualizar alunos e inserir estagiario
    } elseif ($aluno == 2) {
        // Atualiza aluno e insere estagiario usando modelos
        $alunoCadastrado = Aluno::firstWhere('registro', $registro);
        if (!$alunoCadastrado) {
            die("Não foi possivel consultar a tabela alunos");
        }
        $nome = $alunoCadastrado->nome;

        echo "Atualiza aluno e insere estágio. <br>";
        // Atualiza dados do aluno
        $alunoCadastrado->codigo_telefone = $codigo_telefone;
        $alunoCadastrado->telefone = $telefone;
        $alunoCadastrado->codigo_celular = $codigo_celular;
        $alunoCadastrado->celular = $celular;
        $alunoCadastrado->email = $email;
        $alunoCadastrado->identidade = $identidade;
        $alunoCadastrado->orgao = $orgao;
        $alunoCadastrado->cpf = $cpf;
        $alunoCadastrado->nascimento = $data_nascimento;
        $alunoCadastrado->endereco = $endereco;
        $alunoCadastrado->cep = $cep;
        $alunoCadastrado->bairro = $bairro;
        $alunoCadastrado->municipio = $municipio;
        $alunoCadastrado->observacoes = $observacoes;
        $alunoCadastrado->save();

        // Insere novo registro de estagiário
        $novoEstagiario = new Estagiario();
        $novoEstagiario->preencher(array(
            'aluno_id' => $aluno_id,
            'registro' => $registro,
            'nivel' => $nivel,
            'tc' => '0',
            'tc_solicitacao' => date("Y-m-d"),
            'instituicao_id' => $instituicao_id,
            'supervisor_id' => $supervisor_id,
            'professor_id' => $professor_id,
            'periodo' => TC_PERIODO_ATUAL
        ));
        
        if (!$novoEstagiario->save()) {
            die("Não foi possível inserir o registro na tabela estagiarios");
        }
    }

    echo "<meta http-equiv='refresh' content='0;url=../../imprimir/termo.php?registro=$registro&nome=$nome&nivel_romano=$nivel_romano&instituicao=$instituicao&supervisor=$supervisor&cress=$cress&classificacao=$classificacao'>";
    // require_once("../../imprimir/termo.php");
    // die ();
    exit;
}

// Com o registro pesquiso entre os alunos estagiarios usando modelo Aluno
$alunos = Aluno::where('registro = ?', array($registro));

// Verifico se o aluno está cadastrado como estagiario
$quantidade = count($alunos);
// echo $quantidade . "<br>";
// die("Quantidade");
// Aluno nao cadastrado entre os estagiarios portanto, aluno = novo
if ($quantidade == 0) {
    // Busca aluno novo
    $alunosNovos = Aluno::where('registro = ?', array($registro));
    $quantidade_novo = count($alunosNovos);
    // Aluno nao cadastrado na tabela dos alunoNovo
    if ($quantidade_novo == 0) {
        echo "Aluno não cadastrado em estágio. <br />";
        echo "Causa provável: aluno não participou de seleção de estágio. <br />";
        die("Favor enviar um e-mail para <a href='mailto:estagio@ess.ufrj.br'>estagio@ess.ufrj.br</a> informando o seu DRE para verificar a causa do problema.");
    }

    // Aluno novo. Ainda nao iniciou estagio
    foreach ($alunosNovos as $alunoNovo) {
        $aluno_id = $alunoNovo->id;
        $nome = $alunoNovo->nome;
        $codigo_telefone = $alunoNovo->codigo_telefone;
        $telefone = $alunoNovo->telefone;
        $codigo_celular = $alunoNovo->codigo_celular;
        $celular = $alunoNovo->celular;
        $email = $alunoNovo->email;
        $cpf = $alunoNovo->cpf;
        $identidade = $alunoNovo->identidade;
        $orgao = $alunoNovo->orgao;
        $nascimento = $alunoNovo->nascimento;
        // Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa
        $data_sql = date("d-m-Y", strtotime($nascimento));
        // echo $data_sql . "<br>";

        $endereco = $alunoNovo->endereco;
        $cep = $alunoNovo->cep;
        $bairro = $alunoNovo->bairro;
        $municipio = $alunoNovo->municipio;
        $observacoes = $alunoNovo->observacoes;
        $aluno = 0; // Aluno novo buscando estagio
        $nivel = 1; // Tenho que atribuir um nível para a caixa de selecao
    }
}

// Aluno estagiario: ja cadastrado e ainda nao cadastrado
foreach ($alunos as $alunoObj) {
    $aluno_id = $alunoObj->id;
    $nome = $alunoObj->nome;
    $codigo_telefone = $alunoObj->codigo_telefone;
    $telefone = $alunoObj->telefone;
    $codigo_celular = $alunoObj->codigo_celular;
    $celular = $alunoObj->celular;
    $email = $alunoObj->email;
    $cpf = $alunoObj->cpf;
    $identidade = $alunoObj->identidade;
    $orgao = $alunoObj->orgao;
    $nascimento = $alunoObj->nascimento;
    // Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa
    $data_sql = date("d-m-Y", strtotime($nascimento));
    // echo $data_sql . "<br>";

    $endereco = $alunoObj->endereco;
    $cep = $alunoObj->cep;
    $bairro = $alunoObj->bairro;
    $municipio = $alunoObj->municipio;
    $observacoes = $alunoObj->observacoes;
    // echo $observacoes . "<br>";
    
    // Capturo a instituicao atual e anterior usando modelo Estagiario com query raw para join
    $estagiarios = Estagiario::query("select periodo, nivel, estagiarios.instituicao_id, estagiarios.supervisor_id, estagiarios.professor_id
         , instituicoes.instituicao, supervisores.nome
         from estagiarios
         inner join instituicoes on estagiarios.instituicao_id = instituicoes.id
         left join supervisores on estagiarios.supervisor_id = supervisores.id
         where registro = ? order by periodo desc", array($registro));
    
    $quantidade = count($estagiarios);
    if ($quantidade == 0)
        die("Error: Aluno sem estágios");
    $i = 1;
    foreach ($estagiarios as $estagio) {
        $periodo = $estagio->periodo;
        // echo $periodo . "<br>";
        if ($periodo == TC_PERIODO_ATUAL) {
            // echo "Aluno estagiario ja cadastrado no periodo atual. Capturar os ultimos dois estagios<br>";
            // Atualizar alunos e estagiarios
            $aluno = 1; // Aluno estagiando cadastrado
            // echo "Aluno $aluno <br>";
            $instituicao_id_periodo_atual = $estagio->instituicao_id;
            $instituicao_periodo_atual = $estagio->instituicao;

            $supervisor_id_periodo_atual = $estagio->supervisor_id;
            $supervisor_periodo_atual = $estagio->nome;

            $nivel_periodo_atual = $estagio->nivel;
            $periodo_atual = $estagio->periodo;

            // Para o formulario
            $instituicao_id = $instituicao_id_periodo_atual;
            $instituicao = $instituicao_periodo_atual;
            $supervisor_id = $supervisor_id_periodo_atual;
            $supervisor = $supervisor_periodo_atual;

            if ($i == 1) {
                // Como o aluno ja esta cadastrado no periodo atual as duas variaveis sao iguais
                $nivel_formulario = $nivel_periodo_atual;
                $nivel = $nivel_periodo_atual;
            }
        } else {
            // echo "Aluno estagiario nao cadastrado. Capturar somente o ultimo estagio<br>";
            // Atualizar alunos e inserir estagiarios no periodo atual
            // Se nao esta cadastrado no periodo atual, entao classificar como 2
            $aluno = ($aluno == 1) ? 1 : 2; // Aluno estagiando nao cadastrado
            // echo "Aluno $aluno <br>";
            $instituicao_id_periodo_anterior = $estagio->instituicao_id;
            $instituicao_periodo_anterior = $estagio->instituicao;

            $supervisor_id_periodo_anterior = $estagio->supervisor_id;
            $supervisor_periodo_anterior = $estagio->nome;

            $nivel_periodo_anterior = $estagio->nivel;
            $periodo_anterior = $estagio->periodo;

            // Para o formulario
            $instituicao_id = $instituicao_id_periodo_anterior;
            $instituicao = $instituicao_periodo_anterior;
            $supervisor_id = $supervisor_id_periodo_anterior;
            $supervisor = $supervisor_periodo_anterior;

            if ($i == 1) {
                // Como o aluno nao esta cadastrado no periodo atual as duas variaveis sao iguais
                $nivel_formulario = $nivel_periodo_anterior;
                if ($nivel_periodo_anterior == 4) {
                    $nivel = $nivel_periodo_anterior;
                } else {
                    // echo "Aumento em 1 o nivel <br>";
                    $nivel = $nivel_periodo_anterior + 1;
                }
            }
        }

        $i++;
        // Somente os dois ultimos periodos
        if ($i > 2) {
            break;
            // die("Registros capturados");
        }
    }

    // die;
    // echo "<br>";
    switch ($nivel_formulario) {
        case 1:
            $nivel_romano = 'I';
            break;
        case 2:
            $nivel_romano = 'II';
            break;
        case 3:
            $nivel_romano = 'III';
            break;
        case 4:
            $nivel_romano = 'IV';
            break;
    }

    $resultado->MoveNext();
}


// Capturo as instituicoes usando modelo Instituicao
$todasInstituicoes = Instituicao::all();
$i = 1;
foreach ($todasInstituicoes as $inst) {
    $instituicoes[$i]['id'] = $inst->id;
    $instituicoes[$i]['instituicao'] = $inst->instituicao;
    $i++;
}

// Capturo os supervisores usando modelo Supervisor
$todosSupervisores = Supervisor::all();
$i = 1;
foreach ($todosSupervisores as $sup) {
    $supervisores[$i]['id'] = $sup->id;
    $supervisores[$i]['nome'] = $sup->nome;
    $i++;
}

// echo "Nome do estudante (438): " . $nome . "<br>";

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
$smarty->assign("instituicao_id", $instituicao_id);
$smarty->assign("instituicao", $instituicao ?? '');
$smarty->assign("supervisor_id", $supervisor_id);
$smarty->assign("supervisor", $supervisor ?? '');

$smarty->assign("nivel", $nivel); // proximo nivel de estagio
$smarty->assign("nivel_romano", $nivel_romano); // nivel de estagio em numeros romanos
$smarty->assign("aluno", $aluno); // 0 = novo, 1 = estagiario (já cadastrado no periodo atual ou nao)
// Dados das Instituicoes
$smarty->assign("instituicoes", $instituicoes);
// Dados dos Supervisores
$smarty->assign("supervisores", $supervisores);

$smarty->display("alunos-atualizar_atualiza_termo.tpl");

exit;

?>