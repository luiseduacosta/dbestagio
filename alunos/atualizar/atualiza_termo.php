<?php

include_once("../../setup.php");

// print_r($_REQUEST);
// A entrada ao formulario eh atraves do numero de registro
$registro = isset($_REQUEST['registro']) ? $_REQUEST['registro'] : NULL;
// echo "Registro: " . $registro;

if (!ctype_digit($registro)) {
    die("O DRE deve conter apenas carateres numéricos");
}
if (strlen($registro) < 8 or strlen($registro) > 9) {
    die("Quantidade de carateres digitados inválido");
}

// O formulario eh enviado
$submit = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : NULL;
// Tipo de inscricao do aluno: 0 = novo, 1 = cadastrado periodo atual, 2 = nao cadastrado periodo atual
$aluno = isset($_REQUEST['aluno']) ? $_REQUEST['aluno'] : NULL;

$aluno_id = isset($_REQUEST['aluno_id']) ? $_REQUEST['aluno_id'] :  NULL;
$nome = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '';
$codigo_telefone = isset($_REQUEST['codigo_telefone']) ? $_REQUEST['codigo_telefone'] : '';
$telefone = isset($_REQUEST['telefone']) ? $_REQUEST['telefone'] : '';
$codigo_celular = isset($_REQUEST['codigo_celular']) ? $_REQUEST['codigo_celular'] : '';
$celular = isset($_REQUEST['celular']) ? $_REQUEST['celular'] : '';
$email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$identidade = isset($_REQUEST['identidade']) ? $_REQUEST['identidade'] : '';
$orgao = isset($_REQUEST['orgao']) ? $_REQUEST['orgao'] : '';
$cpf = isset($_REQUEST['cpf']) ? $_REQUEST['cpf'] : '';
$nascimento = isset($_REQUEST['nascimento']) ? $_REQUEST['nascimento'] : '';
$endereco = isset($_REQUEST['endereco']) ? $_REQUEST['endereco'] : '';
$cep = isset($_REQUEST['cep']) ? $_REQUEST['cep'] : '';
$bairro = isset($_REQUEST['bairro']) ? $_REQUEST['bairro'] : '';
$municipio = isset($_REQUEST['municipio']) ? $_REQUEST['municipio'] : '';
// $observacoes     = $_REQUEST['observacoes'];
$nivel = isset($_REQUEST['nivel']) ? $_REQUEST['nivel'] : 1;
// Instituicao na qual o aluno estava estagiando no periodo anterior
$instituicao_id_periodo_atual = isset($_REQUEST['instituicao_id_periodo_atual']) ? $_REQUEST['instituicao_id_periodo_atual'] : '0';
$instituicao_id_periodo_anterior = isset($_REQUEST['instituicao_id_periodo_anterior']) ? $_REQUEST['instituicao_id_periodo_anterior'] : '0';

$estagio_id = isset($_REQUEST['estagio_id']) ? $_REQUEST['estagio_id'] : (isset($_REQUEST['id_estagio']) ? $_REQUEST['id_estagio'] : NULL);
$supervisor_id = isset($_REQUEST['supervisor_id']) ? $_REQUEST['supervisor_id'] : NULL;
$instituicao_id = !empty($estagio_id) ? $estagio_id : (isset($_REQUEST['instituicao_id']) ? $_REQUEST['instituicao_id'] : '0');

// O formulario eh enviado pelo aluno
if ($submit) {
    // Verifica o campo instituicao_id
    if (empty($estagio_id))
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
        $muda_estagio = ($estagio_id == $instituicao_id_periodo_anterior) ? 0 : 1;
        // echo "Muda estagio: " . $muda_estagio . "<br>";
        echo "Aluno estagiário.<br>";
        if (($nivel_inicial == 1 or $nivel_inicial == 3) and ($muda_estagio == 1)) { // ($id_estagio != $id_instituicao_periodo_anterior)) {
            $classificacao = 0; // Mudanca fora de regimento
            // echo "Classificacao " . $classificacao . "<br>";
            echo "Mudança de estágio fora do regimento.";
            echo "<br>";
        } elseif (($nivel_inicial == 2) and ($muda_estagio == 1)) { // ($id_estagio != $id_instituicao_periodo_anterior)) {
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

    // Busco primeiramente a informacao na tabela mural_estagio
    $sql_mural = "select id, professor_id, horario from mural_estagio where id=$estagio_id and periodo = '" . TC_PERIODO_ATUAL . "'";
    // echo $sql_mural . "<br>";
    $resultado_mural = $db->Execute($sql_mural);
    $quantidade = $resultado_mural->RecordCount();
    // Logo busco na tabela de estagios
    // echo $quantidade . "<br>";
    if ($quantidade == 0) {
        echo "Campo de estágio não abriu seleção neste período<br>";
        // Calculo em base ao professor que trabalhou com essa instituicao
        $sql_ultimo_estagio = "select professor_id from estagiarios where instituicao_id = '$id_estagio' order by periodo desc";
        // echo $sql_ultimo_estagio . "<br>";
        $resultado_ultimo_estagio = $db->Execute($sql_ultimo_estagio);
        $professor_id = $resultado_ultimo_estagio->fields['professor_id'];
        // echo "<br>";
        // die($sql_ultimo_estagio);
    } else {
        $professor_id = $resultado_mural->fields['professor_id'];
        $turno = $resultado_mural->fields['horario'];
        // die("Estagio");
    } // Agora ja estou com a informacao para ser inserida na tabela estagiarios

    $sql_instituicao = "select instituicao from instituicoes where id=$instituicao_id";
    $res_instituicao = $db->Execute($sql_instituicao);
    $instituicao = $res_instituicao->fields['instituicao'];

    $sql_supervisor = "select cress, nome from supervisores where id=$supervisor_id";
    $resultado_supervisor = $db->Execute($sql_supervisor);
    $supervisor = $resultado_supervisor->fields['nome'];
    $cress = $resultado_supervisor->fields['cress'];

    $data = date("d-m-Y");

    // echo "Aluno estagiario tipo: " . $aluno . "<br>";
    // Inserir aluno e estagiario
    if ($aluno == 0) {
        // Inserir aluno
        $sql_aluno = "select * from alunos where registro = $registro";
        $res_aluno = $db->Execute($sql_aluno);
        if ($res_aluno === false)
            die("Não foi possivel consultar a tabela aluno");

        $nome = $res_aluno->fields['nome'];
        $codigo_telefone = $res_aluno->fields['codigo_telefone'];
        $telefone = $res_aluno->fields['telefone'];
        $codigo_celular = $res_aluno->fields['codigo_celular'];
        $celular = $res_aluno->fields['celular'];
        $email = $res_aluno->fields['email'];
        $cpf = $res_aluno->fields['cpf'];
        $identidade = $res_aluno->fields['identidade'];
        $orgao = $res_aluno->fields['orgao'];
        $data_nascimento = $res_aluno->fields['nascimento'];
        $endereco = $res_aluno->fields['endereco'];
        $cep = $res_aluno->fields['cep'];
        $bairro = $res_aluno->fields['bairro'];
        $municipio = $res_aluno->fields['municipio'];

        echo "Insere aluno e estágio. <br>";
        $sql_alunos = "insert into alunos(registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, ";
        $sql_alunos .= "cpf, identidade, orgao, nascimento, endereco, cep, bairro, municipio) ";
        $sql_alunos .= "values('$registro','$nome','$codigo_telefone', '$telefone', '$codigo_celular', '$celular', '$email', ";
        $sql_alunos .= "'$cpf', '$identidade', '$orgao', '$data_nascimento', '$endereco', '$cep', '$bairro','$municipio')";
        // echo $sql_alunos . "<br>";
        // die();
        $resultado_insere = $db->Execute($sql_alunos);
        if ($resultado_insere === false)
            die("0 Nao foi possível inserir o registro na tabela alunos");
        // Calculo o id do ultimo aluno inserido
        $aluno_id = $db->Insert_ID();

        // $res_ultimo = $db->Execute("select last_insert_id() as aluno_id");
        // if ($res_ultimo === false)
        //     die("Nao foi possivel consultar a sequencia alunos");
        // $aluno_id = $res_ultimo->fields['aluno_id'];
        // echo "Id aluno " . $aluno_id . "<br>";
        // Inserir estagiario
        $sql_estagiarios = "insert into estagiarios(aluno_id, registro, nivel, tc, tc_solicitacao, instituicao_id, supervisor_id, professor_id, periodo) ";
        $sql_estagiarios .= "values('$aluno_id', '$registro', '$nivel', '0', '" . date("Y-m-d") . "','$id_estagio', '$supervisor_id', '$professor_id','" . TC_PERIODO_ATUAL . "')";
        // echo $sql_estagiarios . "<br>";
        // die("Insere estagiario");
        $resultado_insere = $db->Execute($sql_estagiarios);
        if ($resultado_insere === false)
            die("Não foi possível inserir o registro na tabela estagiarios");
        // die;
        // Atualizar alunos e estagiario
    } elseif ($aluno == 1) {
        // Atualiza alunos  e estagiarios

        $sql_alunocadastrado = "select * from alunos where registro = $registro";
        $res_alunocadastrado = $db->Execute($sql_alunocadastrado);
        if ($res_alunocadastrado === false)
            die("Não foi possivel consultar a tabela alunos");
        $nome = $res_alunocadastrado->fields['nome'];

        echo "Atualiza aluno e estágio. <br>";
        $sql_alunos = "update alunos set nome ='$nome', codigo_telefone ='$codigo_telefone', ";
        $sql_alunos .= " telefone ='$telefone', codigo_celular = '$codigo_celular', celular='$celular', email='$email', ";
        $sql_alunos .= " identidade = '$identidade', orgao='$orgao', cpf = '$cpf', nascimento='$data_nascimento', ";
        $sql_alunos .= " endereco='$endereco', cep='$cep', bairro='$bairro', municipio='$municipio', ";
        $sql_alunos .= " observacoes='$observacoes' ";
        $sql_alunos .= " where registro='$registro'";
        // echo $sql_alunos . "<br>";
        // $resultado_insere = $db->Execute($sql_alunos);
        // if ($resultado_insere === false) die("Nao foi possivel atualizar o registro na tabela alunos");

        $sql_estagiarios = "update estagiarios ";
        $sql_estagiarios .= " set nivel = '$nivel', tc = '0', tc_solicitacao = '" . date("Y-m-d") . "', instituicao_id = '$instituicao_id', supervisor_id = '$supervisor_id', professor_id = '$professor_id', periodo = '" . TC_PERIODO_ATUAL . "'";
        $sql_estagiarios .= " where aluno_id='$aluno_id' and periodo= '" . TC_PERIODO_ATUAL . "'";
        // echo $sql_estagiarios . "<br>";
        $resultado_atualiza = $db->Execute($sql_estagiarios);
        if ($resultado_atualiza === false)
            die("1 Não foi possível inserir o registro na tabela estagiarios");
        // echo $sql_estagiarios . "<br>";
        // Atualizar alunos e inserir estagiario
    } elseif ($aluno == 2) {
        // Atualiza aluno e insere estagiario

        $sql_alunocadastrado = "select * from alunos where registro = $registro";
        $res_alunocadastrado = $db->Execute($sql_alunocadastrado);
        if ($res_alunocadastrado === false)
            die("Não foi possivel consultar a tabela alunos");
        $nome = $res_alunocadastrado->fields['nome'];

        echo "Atualiza aluno e insere estágio. <br>";
        $sql_alunos = "update alunos set registro ='$registro', nome ='$nome', codigo_telefone ='$codigo_telefone', ";
        $sql_alunos .= " telefone ='$telefone', codigo_celular = '$codigo_celular', celular='$celular', email='$email', ";
        $sql_alunos .= " identidade = '$identidade', orgao='$orgao', cpf = '$cpf', nascimento='$data_nascimento', ";
        $sql_alunos .= " endereco='$endereco', cep='$cep', bairro='$bairro', municipio='$municipio', ";
        $sql_alunos .= " observacoes='$observacoes' ";
        $sql_alunos .= " where registro='$registro'";
        // echo $sql_alunos . "<br>";
        // $resultado_insere = $db->Execute($sql_alunos);
        // if ($resultado_insere === false) die("Nao foi possivel atualizar o registro na tabela alunos");

        $sql_estagiarios = "insert into estagiarios(aluno_id, registro, nivel, tc, tc_solicitacao, instituicao_id, supervisor_id, professor_id, periodo) ";
        $sql_estagiarios .= "values('$aluno_id', '$registro', '$nivel', '0', '" . date("Y-m-d") . "','$instituicao_id', '$supervisor_id', '$professor_id','" . TC_PERIODO_ATUAL . "')";
        // echo $sql_estagiarios . "<br>";
        $resultado_insere = $db->Execute($sql_estagiarios);
        if ($resultado_insere === false)
            die("Não foi possível inserir o registro na tabela estagiarios");
    }

    echo "<meta http-equiv='refresh' content='0;url=../../imprimir/termo.php?registro=$registro&nome=$nome&nivel_romano=$nivel_romano&instituicao=$instituicao&supervisor=$supervisor&cress=$cress&classificacao=$classificacao'>";
    // require_once("../../imprimir/termo.php");
    // die ();
    exit;
}

// Com o registro pesquiso entre os alunos estagiarios
$sql = "select id, registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, ";
$sql .= "endereco, cep, bairro, municipio, observacoes from alunos where registro='$registro'";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);

if ($resultado === false)
    die("Nao foi possivel consultar a tabela alunos");
// Verifico se o aluno está cadastrado como estagiario
$quantidade = $resultado->RecordCount();
// echo $quantidade . "<br>";
// die("Quantidade");
// Aluno nao cadastrado entre os estagiarios portanto, aluno = novo
if ($quantidade == 0) {

    $sql_novo = "select * from alunosNovos where registro='$registro'";
    // echo $sql_novo;
    // die("Aluno novo");
    $resultado_novo = $db->Execute($sql_novo);
    if ($resultado_novo === false)
        die("Nao foi possivel consultar a tabela alunosNovos");
    $quantidade_novo = $resultado_novo->RecordCount();
    // Aluno nao cadastrado na tabela dos alunoNovo
    if ($quantidade_novo == 0) {
        echo "Aluno não cadastrado em estágio. <br />";
        echo "Causa provável: aluno não participou de seleção de estágio. <br />";
        die("Favor enviar um e-mail para <a href='mailto:estagio@ess.ufrj.br'>estagio@ess.ufrj.br</a> informando o seu DRE para verificar a causa do problema.");
    }

    // Aluno novo. Ainda nao iniciou estagio
    while (!$resultado_novo->EOF) {
        $aluno_id = $resultado_novo->fields['id'];
        $nome = $resultado_novo->fields['nome'];
        $codigo_telefone = $resultado_novo->fields['codigo_telefone'];
        $telefone = $resultado_novo->fields['telefone'];
        $codigo_celular = $resultado_novo->fields['codigo_celular'];
        $celular = $resultado_novo->fields['celular'];
        $email = $resultado_novo->fields['email'];
        $cpf = $resultado_novo->fields['cpf'];
        $identidade = $resultado_novo->fields['identidade'];
        $orgao = $resultado_novo->fields['orgao'];
        $nascimento = $resultado_novo->fields['nascimento'];
        // Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa
        $data_sql = date("d-m-Y", strtotime($nascimento));
        // echo $data_sql . "<br>";

        $endereco = $resultado_novo->fields['endereco'];
        $cep = $resultado_novo->fields['cep'];
        $bairro = $resultado_novo->fields['bairro'];
        $municipio = $resultado_novo->fields['municipio'];
        $observacoes = $resultado_novo->fields['observacoes'];
        $aluno = 0; // Aluno novo buscando estagio
        $nivel = 1; // Tenho que atribuir um nível para a caixa de selecao
        $resultado_novo->MoveNext();
    }
}

// Aluno estagiario: ja cadastrado e ainda nao cadastrado
while (!$resultado->EOF) {
    $aluno_id = $resultado->fields['id'];
    $nome = $resultado->fields['nome'];
    $codigo_telefone = $resultado->fields['codigo_telefone'];
    $telefone = $resultado->fields['telefone'];
    $codigo_celular = $resultado->fields['codigo_celular'];
    $celular = $resultado->fields['celular'];
    $email = $resultado->fields['email'];
    $cpf = $resultado->fields['cpf'];
    $identidade = $resultado->fields['identidade'];
    $orgao = $resultado->fields['orgao'];
    $nascimento = $resultado->fields['nascimento'];
    // Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa
    $data_sql = date("d-m-Y", strtotime($nascimento));
    // echo $data_sql . "<br>";

    $endereco = $resultado->fields['endereco'];
    $cep = $resultado->fields['cep'];
    $bairro = $resultado->fields['bairro'];
    $municipio = $resultado->fields['municipio'];
    $observacoes = $resultado->fields['observacoes'];
    // echo $observacoes . "<br>";
    // Capturo a instituicao atual e anterior
    $sql_periodos = "select periodo, nivel, estagiarios.instituicao_id, estagiarios.supervisor_id, estagiarios.professor_id
	 , instituicoes.instituicao, supervisores.nome
	 from estagiarios
	 inner join instituicoes on estagiarios.instituicao_id = instituicoes.id
	 left join supervisores on estagiarios.supervisor_id = supervisores.id
	 where registro = '$registro' order by periodo desc";
    // echo $sql_periodos . "<br>";
    $resultado_periodo = $db->Execute($sql_periodos);
    $quantidade = $resultado_periodo->RecordCount();
    if ($quantidade == 0)
        die("Error: Aluno sem estágios");
    $i = 1;
    while (!$resultado_periodo->EOF) {
        $periodo = $resultado_periodo->fields['periodo'];
        // echo $periodo . "<br>";
        if ($periodo == TC_PERIODO_ATUAL) {
            // echo "Aluno estagiario ja cadastrado no periodo atual. Capturar os ultimos dois estagios<br>";
            // Atualizar alunos e estagiarios
            $aluno = 1; // Aluno estagiando cadastrado
            // echo "Aluno $aluno <br>";
            $instituicao_id_periodo_atual = $resultado_periodo->fields['instituicao_id'];
            $instituicao_periodo_atual = $resultado_periodo->fields['instituicao'];

            $supervisor_id_periodo_atual = $resultado_periodo->fields['supervisor_id'];
            $supervisor_periodo_atual = $resultado_periodo->fields['nome'];

            $nivel_periodo_atual = $resultado_periodo->fields['nivel'];
            $periodo_atual = $resultado_periodo->fields['periodo'];

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
            $instituicao_id_periodo_anterior = $resultado_periodo->fields['instituicao_id'];
            $instituicao_periodo_anterior = $resultado_periodo->fields['instituicao'];

            $supervisor_id_periodo_anterior = $resultado_periodo->fields['supervisor_id'];
            $supervisor_periodo_anterior = $resultado_periodo->fields['nome'];

            $nivel_periodo_anterior = $resultado_periodo->fields['nivel'];
            $periodo_anterior = $resultado_periodo->fields['periodo'];

            // Para o formulario
            $instituicao_id = $instituicao_id_periodo_anterior;
            $instituicao = $instituicao_periodo_anterior;
            $supervisor_id = $supervisor_id_periodo_anterior;
            $supervisor = $supervisor_periodo_anterior;

            if ($i == 1) {
                // Como o aluno nao esta cadastrado no periodo atual as duas variaveis sao diferentes
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
        $resultado_periodo->MoveNext();
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


// Capturo as instituicoes
$sql_instituicoes = "select id, instituicao from instituicoes order by instituicao";
$resposta_instituicoes = $db->Execute($sql_instituicoes);
if ($resposta_instituicoes === false)
    die("Nao foi possivel consultar a tabela instituicoes");
$i = 1;
while (!$resposta_instituicoes->EOF) {
    $instituicoes[$i]['id'] = $resposta_instituicoes->fields['id'];
    $instituicoes[$i]['instituicao'] = $resposta_instituicoes->fields['instituicao'];
    $resposta_instituicoes->MoveNext();
    $i++;
}

// Capturo os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$resposta_supervisores = $db->Execute($sql_supervisores);
if ($resposta_supervisores === false)
    die("Nao foi possivel consultar a tabela supervisores");
$i = 1;
while (!$resposta_supervisores->EOF) {
    $supervisores[$i]['id'] = $resposta_supervisores->fields['id'];
    $supervisores[$i]['nome'] = $resposta_supervisores->fields['nome'];
    $resposta_supervisores->MoveNext();
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
