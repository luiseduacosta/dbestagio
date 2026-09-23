<?php

include_once("../../autentica.inc");
// echo "Origem: " .  $url . "<br>";

$url = $_SERVER['SERVER_NAME'];
$origem = $_REQUEST['origem'];
// echo $_SERVER['PHP_SELF'] . " " . $origem . "<br>";
// Se o programa foi chamado desde seleciona.php retorna a ele proprio
if (substr_count($origem, "seleciona.php") == 1) {
    $origem = $_SERVER['PHP_SELF'];
} elseif (substr_count($origem, "listar_dae.php") == 1) {
    $origem = "http://$url/estagio/alunos/exibir/listar_dae.php";
}

if (empty($origem))
    $origem = $_SERVER['HTTP_REFERER'];

// echo "Origem: " . $origem . "<br>";
if ($debug == 1) {
    echo $origem . "<br>";
    echo $_SERVER['PHP_SELF'] . "<br>";
}

// Alunos
$aluno_id = $_REQUEST['aluno_id'];
$registro = $_REQUEST['registro'];
$nome = $_REQUEST['nome'];
$codigo_telefone = $_REQUEST['codigo_telefone'];
$telefone = $_REQUEST['telefone'];
$codigo_celular = $_REQUEST['codigo_celular'];
$celular = $_REQUEST['celular'];
$email = $_REQUEST['email'];
$identidade = $_REQUEST['identidade'];
$orgao = $_REQUEST['orgao'];
$cpf = $_REQUEST['cpf'];
$nascimento = $_REQUEST['nascimento'];
$endereco = $_REQUEST['endereco'];
$cep = $_REQUEST['cep'];
$bairro = $_REQUEST['bairro'];
$municipio = $_REQUEST['municipio'];
$observacoes = $_REQUEST['observacoes'];

// echo $nascimento . "<br>";

// Estagiarios
$estagiario_id = $_POST['estagiario_id'];
$periodo = $_POST['periodo'];
$nivel = $_POST['nivel'];
$turno = $_POST['turno'];
$tc = $_POST['tc'];
$instituicao_id = $_POST['instituicao_id'];
$supervisor_id = $_POST['supervisor_id'];
$professor_id = $_POST['professor_id'];
$nota = $_POST['nota'];
$ch = $_POST['ch'];

$acao = $_REQUEST['acao'];
$envio = $_REQUEST['submit'];
$cadastro = $_REQUEST['valorcadastro'];

if ($debug == 1) {
    echo "Acao " . $acao . "<br>";
    echo "Cadastro " . $cadastro . "<br>";
    echo "Atualizar estagio " . $atualizar_estagio . "<br>";
}

// echo "Id estagiario " . $id_estagiarios . " - " . $_REQUEST['id_estagiarios'] . "<br>";
// Se ja esta cadastrado
if (($acao == 1) || ($cadastro == 1)) {
    // echo "Acao ou cadastro" . "<br>";
    // die();
    // Atualiza somente tabela estagiarios
    if (!empty($estagiario_id)) {
        $sql_estagiarios = "update estagiarios set aluno_id='$aluno_id', registro='$registro', ";
        $sql_estagiarios .= " nivel='$nivel', periodo='$periodo', tc='$tc', ";
        $sql_estagiarios .= " supervisor_id='$supervisor_id', instituicao_id='$instituicao_id', professor_id='$professor_id', ";
        $sql_estagiarios .= " nota='$nota', ch='$ch' ";
        $sql_estagiarios .= " where id='$estagiario_id'";
        // echo $sql_estagiarios . "<br>";
        $resultado_insere = $db->Execute($sql_estagiarios);
        if ($resultado_insere === false)
            die("Nao foi possivel atualizar o registro na tabela estagiarios");
    } else {
        // Atualiza somente tabela alunos
        // Para salvar tenho que utilizar o formato aaaa/mm/dd/
        $novoNascimento = explode("/", $nascimento);
        $data_nascimento = $novoNascimento[2] . "-" . $novoNascimento[1] . "-" . $novoNascimento[0];

        $sql_alunos = "update alunos set registro ='$registro', nome ='$nome', codigo_telefone ='$codigo_telefone', ";
        $sql_alunos .= " telefone ='$telefone', codigo_celular = '$codigo_celular', celular='$celular', email='$email', ";
        $sql_alunos .= " identidade = '$identidade', orgao = '$orgao', cpf = '$cpf', nascimento='$data_nascimento', ";
        $sql_alunos .= " endereco='$endereco', cep='$cep', bairro='$bairro', municipio='$municipio', ";
        $sql_alunos .= " observacoes='$observacoes' ";
        $sql_alunos .= " where id='$aluno_id'";
        // echo $sql_alunos . "<br>";
        $resultado_insere = $db->Execute($sql_alunos);
        if ($resultado_insere === false)
            die("Nao foi possivel atualizar o registro na tabela alunos");
        // Atualizo tambem o campo registro na tabela estagiarios
        $sql_registro = "update estagiarios set registro='$registro' where aluno_id='$aluno_id'";
        $resultado_registro = $db->Execute($sql_registro);
        if ($resultado_registro === false)
            die("Nao foi possivel atualizar o campo registro na tabela estagiarios");
    }

    // echo "ORIGEM: " . $origem . "<br>";
    /* Quando atualiza volta para ver_cada.php menos no caso de ter sido chamado desde listar.php */   
    $buscastring = substr_count($origem, "listar.php");
    // echo "Busca string: " .  $buscastring . " Origem: " . $origem . "<br>";
    // echo "Location: $origem";
    if (substr_count($origem, "listar.php") == 1) {
        // echo "Listar " . "<br>";
        header('Location:'. $origem);
        // header('Location: ../exibir/listar.php');
        exit;
    } else {
        // echo "Exibir " . "<br>";
        header("Location: ../exibir/ver_cada.php?aluno_id=$aluno_id");
        exit;
    }
    exit;
}

// Aluno
$sql = "select registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, ";
$sql .= "endereco, cep, bairro, municipio, observacoes from alunos where id='$aluno_id'";
// echo $sql . "<br>";

if ($debug == 1)
    echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado === false)
    die("Nao foi possivel consultar a tabela alunos");
while (!$resultado->EOF) {
    // $aluno_id = $resultado->fields['id'];
    $registro = $resultado->fields['registro'];
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
    $nova_data = ereg_replace("-", "/", $nascimento);
    // echo "Nova data: ". $nova_data . "<br>";
    $dataCorrigida = explode("/", $nova_data);
    $data_sql = $dataCorrigida[2] . "/" . $dataCorrigida[1] . "/" . $dataCorrigida[0];
    // echo $data_sql . "<br>";

    $endereco = $resultado->fields['endereco'];
    $cep = $resultado->fields['cep'];
    $bairro = $resultado->fields['bairro'];
    $municipio = $resultado->fields['municipio'];
    $observacoes = $resultado->fields['observacoes'];
    // echo $observacoes . "<br>";
    $resultado->MoveNext();
}

// Estagiarios
$sql_estagiarios = "select * from estagiarios where aluno_id=$aluno_id order by periodo";
// echo $sql_estagiarios . "<br>";
$resultado_estagiario = $db->Execute($sql_estagiarios);
if ($resultado_estagiario === false)
    die("Nao foi possivel consultar a tabela estagiarios");
$i = 0;
while (!$resultado_estagiario->EOF) {
    $estagiarios[$i]['id'] = $resultado_estagiario->fields["id"];
    $estagiarios[$i]['tc'] = $resultado_estagiario->fields["tc"];
    $estagiarios[$i]['periodo'] = $resultado_estagiario->fields["periodo"];
    $estagiarios[$i]['turno'] = NULL;
    $estagiarios[$i]['nivel'] = $resultado_estagiario->fields["nivel"];
    $estagiarios[$i]['instituicao_id'] = $resultado_estagiario->fields["instituicao_id"];
    $estagiarios[$i]['supervisor_id'] = $resultado_estagiario->fields["supervisor_id"];
    $estagiarios[$i]['professor_id'] = $resultado_estagiario->fields["professor_id"];
    $estagiarios[$i]['nota'] = $resultado_estagiario->fields["nota"];
    $estagiarios[$i]['ch'] = $resultado_estagiario->fields["ch"];

    $curr_inst_id = $resultado_estagiario->fields["instituicao_id"];
    $curr_super_id = $resultado_estagiario->fields["supervisor_id"];
    $curr_prof_id = $resultado_estagiario->fields["professor_id"];

    // Instituicao
    if (!empty($curr_inst_id)) {
        $sql_instituicao = "select id, instituicao from instituicoes where id = " . (int)$curr_inst_id;
        $res_instituicao = $db->Execute($sql_instituicao);
        if ($res_instituicao && !$res_instituicao->EOF) {
            $estagiarios[$i]['instituicao'] = $res_instituicao->fields["instituicao"];
        } else {
            $estagiarios[$i]['instituicao'] = "Sem dados";
        }
    } else {
        $estagiarios[$i]['instituicao'] = "Sem dados";
    }

    // Supervisor
    if (!empty($curr_super_id)) {
        $sql_nome_supervisor = "select nome from supervisores where id = " . (int)$curr_super_id;
        $resultado_nome_supervisor = $db->Execute($sql_nome_supervisor);
        if ($resultado_nome_supervisor && !$resultado_nome_supervisor->EOF) {
            $estagiarios[$i]['supervisor'] = $resultado_nome_supervisor->fields["nome"];
        } else {
            $estagiarios[$i]['supervisor'] = "Sem dados";
        }
    } else {
        $estagiarios[$i]['supervisor'] = "Sem dados";
    }

    // Professor
    if (!empty($curr_prof_id)) {
        $sql_nome_professor = "select nome from professores where id = " . (int)$curr_prof_id;
        $resultado_nome_professor = $db->Execute($sql_nome_professor);
        if ($resultado_nome_professor && !$resultado_nome_professor->EOF) {
            $estagiarios[$i]['professor'] = $resultado_nome_professor->fields["nome"];
        } else {
            $estagiarios[$i]['professor'] = "Sem dados";
        }
    } else {
        $estagiarios[$i]['professor'] = "Sem dados";
    }

    $resultado_estagiario->MoveNext();
    $i++;
}

// Capturo a informacao sobre as instituicoes
$instituicoes = array();
$sql = "select id, instituicao from instituicoes order by instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false)
    die("Nao foi possivel consultar a tabela instituicoes");
$i = 0;
while (!$resultado->EOF) {
    $instituicoes[$i]['instituicao_id'] = $resultado->fields['id'];
    $instituicoes[$i]['instituicao'] = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

// Capturo a informacao sobre os supervisores
$supervisores = array();
$sql_supervisores = "select id, nome from supervisores order by nome";
$resultado_supervisores = $db->Execute($sql_supervisores);
if ($resultado_supervisores === false)
    die("Nao foi possivel consultar a tabela supervisores");
$i = 0;
while (!$resultado_supervisores->EOF) {
    $supervisores[$i]['supervisor_id'] = $resultado_supervisores->fields['id'];
    $supervisores[$i]['supervisor'] = $resultado_supervisores->fields['nome'];
    $resultado_supervisores->MoveNext();
    $i++;
}

// Capturo a informacao sobre os professores
$professores = array();
$sql_professores = "select id, nome from professores order by nome";
$resultado_professores = $db->Execute($sql_professores);
if ($resultado_professores === false)
    die("Nao foi possivel consultar a tabela professores");
$i = 0;
while (!$resultado_professores->EOF) {
    $professores[$i]['professor_id'] = $resultado_professores->fields['id'];
    $professores[$i]['professor'] = $resultado_professores->fields['nome'];
    $resultado_professores->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("origem", $origem);
// Aluno
$smarty->assign("aluno_id", $aluno_id);

$smarty = new Smarty_estagio;
$smarty->assign("origem", $origem);
// Aluno
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
$smarty->assign("observacoes", $observacoes);
// Estagios
$smarty->assign("estagiarios", $estagiarios);
// Instituicoes
$smarty->assign("instituicoes", $instituicoes);
// Supervisores
$smarty->assign("supervisores", $supervisores);
// Professores
$smarty->assign("professores", $professores);

$smarty->display("alunos-atualizar_atualiza.tpl");

exit;
?>
