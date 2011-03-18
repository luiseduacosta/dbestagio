<?php

// include_once("../../autentica.inc");

include_once("../../setup.php");

$origem = $_REQUEST['origem'];
// echo $_SERVER['PHP_SELF'] . " " . $origem . "<br>";

if(empty($origem))
    $origem = $_SERVER['HTTP_REFERER'];


if($debug == 1) {
    echo $origem . "<br>";
    echo $_SERVER['PHP_SELF'] . "<br>";
}

// Alunos
$id_aluno        = $_REQUEST['id_aluno'];
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
// $observacoes     = $_REQUEST['observacoes'];

// echo $nascimento . "<br>";

if($debug == 1) {
    // print_r($_REQUEST) . "<br>";
	}

$acao     = $_REQUEST['acao'];
$envio    = $_REQUEST['submit'];
$cadastro = $_REQUEST['valorcadastro'];

if($debug == 1) {
    echo "Acao " . $acao . "<br>";
    echo "Cadastro " . $cadastro . "<br>";
    echo "Atualizar estagio ". $atualizar_estagio . "<br>";
}

// Aluno
$sql  = "select registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, ";
$sql .= "endereco, cep, bairro, municipio, observacoes from alunos where registro='$registro'";

if($debug == 1)
    echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela alunos");
while (!$resultado->EOF) {
	// $id_aluno = $resultado->fields['id'];
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
	$nova_data = ereg_replace("-","/",$nascimento);
	// echo "Nova data: ". $nova_data . "<br>";
	$dataCorrigida = explode("/",$nova_data);
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

$smarty = new Smarty_estagio;
$smarty->assign("origem",$origem);
// Aluno
$smarty->assign("id_aluno",$id_aluno);
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
// $smarty->assign("observacoes",$observacoes);

$smarty->display("alunos-cadastro_termo.tpl");

exit;

?>
