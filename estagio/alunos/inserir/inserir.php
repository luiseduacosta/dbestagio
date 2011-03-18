<?php

if($debug == 1) {
  echo $_SERVER['PHP_SELF'];
}

include_once("../../autentica.inc");

include_once("../../setup.php");

$cadastro        = $_POST['cadastro'];
$registro        = $_POST['registro'];
$nome            = $_POST['nome'];
$codigo_telefone = $_POST['codigo_telefone'];
$telefone        = $_POST['telefone'];
$codigo_celular  = $_POST['codigo_celular'];
$celular         = $_POST['celular'];
$email           = $_POST['email'];
$cpf             = $_POST['cpf'];
$identidade      = $_POST['identidade'];
$orgao           = $_POST['orgao'];
$nascimento      = $_POST['nascimento'];
$endereco        = $_POST['endereco'];
$cep             = $_POST['cep'];
$bairro          = $_POST['bairro'];
$municipio       = $_POST['municipio'];

// echo "Cadastro " . $cadastro . "<br>";

// Para salvar tenho que utilizar o formato aaaa/mm/dd/
$novoNascimento = explode("/",$nascimento);
$data_nascimento = $novoNascimento[2] . "-" . $novoNascimento[1] . "-" . $novoNascimento[0];

if($cadastro == 0) {

    $sql_alunos  = "insert into alunos(registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, ";
    $sql_alunos .= "cpf, identidade, orgao, nascimento, endereco, cep, bairro, municipio) ";
    $sql_alunos .= "values('$registro','$nome','$codigo_telefone', '$telefone', '$codigo_celular', '$celular', '$email', ";
    $sql_alunos .= "'$cpf', '$identidade', '$orgao', '$data_nascimento', '$endereco', '$cep', '$bairro','$municipio')";
    // echo $sql_alunos . "<br>";

    $resultado_insere = $db->Execute($sql_alunos);

    if($resultado_insere === false) die ("Nao foi possi­vel inserir o registro na tabela alunos");

    $res_ultimo = $db->Execute("select max(id) as ultimo_aluno from alunos");
    if($res_ultimo === false) die ("Nao foi possivel consultar a sequencia alunos");
    $ultimo_aluno = $res_ultimo->fields['ultimo_aluno'];

    header("Location:../exibir/ver_cada.php?id_aluno=$ultimo_aluno");
    // header("Location:acrescentar_estagio.php?id_aluno=$ultimo_aluno");
    exit;
}

exit;

?>