<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$cadastro        = isset($_POST['valorcadastro']) ? (int)$_POST['valorcadastro'] : 0;
$registro        = isset($_POST['registro']) ? trim($_POST['registro']) : '';
$nome            = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$codigo_telefone = isset($_POST['codigo_telefone']) ? (int)$_POST['codigo_telefone'] : 21;
$telefone        = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
$codigo_celular  = isset($_POST['codigo_celular']) ? (int)$_POST['codigo_celular'] : 21;
$celular         = isset($_POST['celular']) ? trim($_POST['celular']) : '';
$email           = strtolower(trim(isset($_POST['email']) ? $_POST['email'] : ''));
$cpf             = isset($_POST['cpf']) ? trim($_POST['cpf']) : '';
$identidade      = isset($_POST['identidade']) ? trim($_POST['identidade']) : '';
$orgao           = isset($_POST['orgao']) ? trim($_POST['orgao']) : '';
$nascimento      = isset($_POST['nascimento']) ? trim($_POST['nascimento']) : '';
$endereco        = isset($_POST['endereco']) ? trim($_POST['endereco']) : '';
$cep             = isset($_POST['cep']) ? trim($_POST['cep']) : '';
$bairro          = isset($_POST['bairro']) ? trim($_POST['bairro']) : '';
$municipio       = isset($_POST['municipio']) ? trim($_POST['municipio']) : '';

if ($nome === '') {
    die("Informe o nome do aluno.");
}
if ($registro === '' || !ctype_digit($registro)) {
    die("Informe um registro (matrícula) válido.");
}
if (Aluno::registroDuplicado($registro) !== null) {
    header("Location:../exibir/ver_cada.php?registro=" . (int)$registro);
    exit;
}

// Converte a data dd/mm/aaaa para o formato do banco (aaaa-mm-dd).
$data_nascimento = '';
if ($nascimento !== '') {
    if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $nascimento, $m)) {
        $data_nascimento = $m[3] . "-" . $m[2] . "-" . $m[1];
    } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $nascimento)) {
        $data_nascimento = $nascimento;
    }
}

$novo = new Aluno();
$novo->id              = Aluno::proximoId(); // id não é auto_increment.
$novo->registro        = (int)$registro;
$novo->nome            = $nome;
$novo->codigo_telefone = $codigo_telefone;
$novo->telefone        = $telefone;
$novo->codigo_celular  = $codigo_celular;
$novo->celular         = $celular;
$novo->email           = $email;
$novo->cpf             = $cpf;
$novo->identidade      = $identidade;
$novo->orgao           = $orgao;
$novo->nascimento      = $data_nascimento !== '' ? $data_nascimento : null;
$novo->endereco        = $endereco;
$novo->cep             = $cep;
$novo->bairro          = $bairro;
$novo->municipio       = $municipio;
$novo->estagiarios_count = 0;
$novo->inscricao_count = 0;

if (!$novo->save()) {
    error_log("Erro ao inserir aluno: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível inserir o registro na tabela alunos.");
}

header("Location:../exibir/ver_cada.php?aluno_id=" . $novo->id);
exit;

?>