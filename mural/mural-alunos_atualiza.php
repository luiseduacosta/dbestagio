<?php

/*
 * Created on 13/06/2006
 */

include_once("../setup.php");

$sistema_autentica = $_REQUEST['sistema_autentica'];

$aluno = $_REQUEST['aluno']; // Novo ou ja conhecido
$aluno_id = $_REQUEST['aluno_id'];
$muralestagio_id = $_REQUEST['muralestagio_id'];

$nome = $_REQUEST['nome'];
$registro = $_REQUEST['registro'];
$codigo_telefone = $_REQUEST['codigo_telefone'];
$telefone = $_REQUEST['telefone'];
$codigo_celular = $_REQUEST['codigo_celuar'];
$celular = $_REQUEST['celular'];
$email = $_REQUEST['email'];
$cpf = $_REQUEST['cpf'];
$identidade = $_REQUEST['identidade'];
$orgao = $_REQUEST['orgao'];
$nascimento = $_REQUEST['nascimento'];
$endereco = $_REQUEST['endereco'];
$cep = $_REQUEST['cep'];
$municipio = $_REQUEST['municipio'];
$bairro = $_REQUEST['bairro'];

$instituicao = $_REQUEST['instituicao'];

// echo "Sistema autentica " . $sistema_autentica . "<br>";

if ($sistema_autentica == 1) {

    if (empty($codigo_telefone)) {
        $codigo_telefone = 21;
    }

    if (empty($codigo_celular)) {
        $codigo_celular = 21;
    }

    if (empty($nascimento))
        $dataSQL = "";
    else
        $dataSQL = date("Y-m-d", strtotime($nascimento));

    $dbase = " alunos ";

    $sql = "update " . $dbase . " set " .
            "nome='$nome', " .
            "codigo_telefone ='$codigo_telefone', " .
            "telefone='$telefone', " .
            "codigo_celular='$codigo_celular', " .
            "celular='$celular', " .
            "email='$email', " .
            "cpf='$cpf', " .
            "identidade='$identidade', " .
            "orgao='$orgao', " .
            "nascimento='$dataSQL', " .
            "endereco='$endereco', " .
            "cep='$cep', " .
            "municipio='$municipio', " .
            "bairro='$bairro' " .
            "where registro='$registro'";


    $resultado = $db->Execute($sql);
    if ($resultado === false) die("Não foi possível atualizar a tabela alunos");
}

// Insere inscricao para selecao de estagio
if (!empty($muralestagio_id)) {
    $data = date("Y-m-j");

    // Capturo o valor do PERIODO_ATUAL
    $periodo = PERIODO_ATUAL;

    // Verifico se o aluno já fez inscricao nesta seleção
    $sql = "select id from inscricoes where registro='$registro' and muralestagio_id='$muralestagio_id' and periodo='$periodo'";
    // echo $sql . "<br>";
    // die("Verifico se ja fez inscricao");
    $resultado = $db->Execute($sql);
    $quantidade = $resultado->RecordCount($ql);
    if ($quantidade > 0) {
        echo "Inscrição já realizada" . "<br>";
        header("Location:listaInscritos.php?muralestagio_id=$muralestagio_id");
        die("Inscricao ja realizada!");
    }

    // Busco o id do aluno na tabela alunos
    $sql_aluno = "select id from alunos where registro='$registro'";
    $res_aluno = $db->Execute($sql_aluno);
    $aluno_id = $res_aluno->fields['id'];
    if (!$aluno_id) die ("Não foi possível encontrar o aluno com registro $registro na tabela alunos");

    $sql_inserir = "insert into inscricoes (registro, muralestagio_id, data, periodo, aluno_id) " .
            "values('$registro','$muralestagio_id','$data','$periodo','$aluno_id')";
    // die("Inserir inscrição para estágio");
    $resultadoInserir = $db->Execute($sql_inserir);

    if ($resultadoInserir === false)
        die("Não foi possível inserir o registro na tabela inscricoes");

    header("Location:listaInscritos.php?muralestagio_id=$muralestagio_id");

    exit;
}

header("Location:ver-aluno.php?registro={$registro}");

exit;

?>
