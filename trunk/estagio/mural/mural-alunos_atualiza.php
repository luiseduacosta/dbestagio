<?php

/*
 * Created on 13/06/2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../setup.php");

$sistema_autentica = $_REQUEST['sistema_autentica'];
$aluno = $_REQUEST['aluno']; // Novo ou ja conhecido
$id_aluno = $_REQUEST['id_aluno'];
$id_instituicao = $_REQUEST['id_instituicao'];

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

    /*
      echo "Tipo: " . $aluno . "<br>";
      echo "Id Aluno: " . $id_aluno . "<br>";
      echo "Id Instituição: " . $id_instituicao . "<br>";
      echo "Registro: " . $registro . "<br>";
     */

// Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa
// echo "Nascimento (atualizaInsere.php) " . $nascimento . "<br>";
// $nova_data = ereg_replace("-","/",$nascimento);
// echo "Nova data: ". $nova_data . "<br>";
// $dataCorrigida = explode("/",$nova_data);
// $dataSQL = $dataCorrigida[2] . "/" . $dataCorrigida[1] . "/" . $dataCorrigida[0];

    if (empty($nascimento))
        $dataSQL = "";
    else
        $dataSQL = date("Y-m-d", strtotime($nascimento));

// Se não eh "novo" atualiza a tabela alunos
    if ($aluno == 1) {
        $dbase = " alunos ";
    } elseif ($aluno == 0) {
        $dbase = " alunosNovos ";
    }

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
// "where id='$id_aluno'";
            "where registro='$registro'";

// echo $sql . "<br>";
// die();

    $resultado = $db->Execute($sql);
    if ($resultado === false) die("Não foi possível atualizar a tabela alunos ou a tabela alunosNovos");
}

// Insere inscricao para selecao de estagio
if (!empty($id_instituicao)) {
    $data = date("Y-m-j");

    // Capturo o valor do PERIODO_ATUAL
    $periodo = PERIODO_ATUAL;

    // Verifico se o aluno já fez inscricao nesta seleção
    $sql = "select id from mural_inscricao where id_aluno='$registro' and id_instituicao='$id_instituicao' and periodo='$periodo'";
    // echo $sql . "<br>";
    // die("Verifico se ja fez inscricao");
    $resultado = $db->Execute($sql);
    $quantidade = $resultado->RecordCount($ql);
    if ($quantidade > 0) {
        echo "Inscrição já realizada" . "<br>";
        header("Location:listaInscritos.php?id_instituicao=$id_instituicao");
        die("Inscricao ja realizada!");
    }

    $sql_inserir = "insert into mural_inscricao (id_aluno,id_instituicao,data,periodo) " .
            "values('$registro','$id_instituicao','$data','$periodo')";
    // echo $sql_inserir . "<br>";
    // die("Inserir inscrição para estágio");
    $resultadoInserir = $db->Execute($sql_inserir);

    if ($resultadoInserir === false)
        die("Não foi possível inserir o registro na tabela mural_inscricao");

    header("Location:listaInscritos.php?id_instituicao=$id_instituicao");

    exit;
}

header("Location:ver-aluno.php?id_aluno={$registro}&aluno={$aluno}");

exit;

?>
