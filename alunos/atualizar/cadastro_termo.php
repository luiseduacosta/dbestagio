<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$origem = isset($_REQUEST['origem']) ? $_REQUEST['origem'] : '';
// echo $_SERVER['PHP_SELF'] . " " . $origem . "<br>";

if (empty($origem))
    $origem = $_SERVER['HTTP_REFERER'];

// Alunos
$aluno_id        = $_REQUEST['aluno_id'];
$registro        = $_REQUEST['registro'];
$nome            = $_REQUEST['nome'];
$codigo_telefone = $_REQUEST['codigo_telefone'];
$telefone        = $_REQUEST['telefone'];
$codigo_celular  = $_REQUEST['codigo_celular'];
$celular         = $_REQUEST['celular'];
$email           = $_REQUEST['email'];
$identidade      = $_REQUEST['identidade'];
$orgao           = $_REQUEST['orgao'];
$cpf             = $_REQUEST['cpf'];
$nascimento      = $_REQUEST['nascimento'];
$endereco        = $_REQUEST['endereco'];
$cep             = $_REQUEST['cep'];
$bairro          = $_REQUEST['bairro'];
$municipio       = $_REQUEST['municipio'];
$observacoes     = $_REQUEST['observacoes'];

$acao     = $_REQUEST['acao'];
$envio    = $_REQUEST['submit'];
$cadastro = $_REQUEST['valorcadastro'];

// Aluno - Usando modelo Aluno
$aluno = Aluno::firstWhere("registro = ?", array($registro));
if (!$aluno) die ("Nao foi possivel consultar a tabela alunos");

$aluno_id = $aluno->id;
$registro = $aluno->registro;
$nome = $aluno->nome;
$codigo_telefone = $aluno->codigo_telefone;
$telefone = $aluno->telefone;
$codigo_celular = $aluno->codigo_celular;
$celular = $aluno->celular;
$email = $aluno->email;
$cpf = $aluno->cpf;
$identidade = $aluno->identidade;
$orgao = $aluno->orgao;
$nascimento = $aluno->nascimento;
	
// Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa
$nova_data = ereg_replace("-","/",$nascimento);
// echo "Nova data: ". $nova_data . "<br>";
$dataCorrigida = explode("/",$nova_data);
$data_sql = $dataCorrigida[2] . "/" . $dataCorrigida[1] . "/" . $dataCorrigida[0];
// echo $data_sql . "<br>";
	
$endereco = $aluno->endereco;
$cep = $aluno->cep;
$bairro = $aluno->bairro;
$municipio = $aluno->municipio;
$observacoes = $aluno->observacoes;
// echo $observacoes . "<br>";

$smarty = new Smarty_estagio;
$smarty->assign("origem",$origem);
// Aluno
$smarty->assign("aluno_id",$aluno_id);
$smarty->assign("registro",$registro);
$smarty->assign("aluno_nome",$nome);
$smarty->assign("codigo_telefone",$codigo_telefone);
$smarty->assign("telefone",$telefone);
$smarty->assign("codigo_celular",$codigo_celular);
$smarty->assign("celular",$celular);
$smarty->assign("email",$email);
$smarty->assign("cpf",$cpf);
$smarty->assign("identidade",$identidade);
$smarty->assign("orgao",$orgao);
$smarty->assign("nascimento",$data_sql);
$smarty->assign("endereco",$endereco);
$smarty->assign("cep",$cep);
$smarty->assign("bairro",$bairro);
$smarty->assign("municipio",$municipio);
$smarty->assign("observacoes",$observacoes);

$smarty->display("alunos-cadastro_termo.tpl");

?>