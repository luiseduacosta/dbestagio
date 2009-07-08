<?php

// echo $_SERVER['PHP_SELF'] . "<br>";

// include_once("../../autentica.inc");
include_once("../../db.inc");
include_once("../../setup.php");

$origem = $_REQUEST['origem'];
// echo $_SERVER['PHP_SELF'] . " " . $origem . "<br>";
$url = $_SERVER['SERVER_NAME'];
// Se o programa foi chamado desde seleciona.php retorna a ele proprio
if (substr_count($origem,"seleciona.php") == 1) {
	$origem = $_SERVER['PHP_SELF'];	
} elseif (substr_count($origem,"listar_dae.php") == 1) {
	$origem = "http://$url/estagio/alunos/exibir/listar_dae.php";
}

if(empty($origem))
    $origem = $_SERVER['HTTP_REFERER'];


if($debug == 1) {
    echo $origem . "<br>";
    echo $_SERVER['PHP_SELF'] . "<br>";
}

$submit = $_REQUEST['submit'];
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

// echo "Id estagiario " . $id_estagiarios . " - " . $_REQUEST['id_estagiarios'] . "<br>";

// Se ja esta cadastrado
if($submit)  {

	// Para salvar tenho que utilizar o formato aaaa/mm/dd/
	$novoNascimento = split("/",$nascimento);
	$data_nascimento = $novoNascimento[2] . "-" . $novoNascimento[1] . "-" . $novoNascimento[0];
		
	$sql_alunos  = "update alunos set registro ='$registro', nome ='$nome', codigo_telefone ='$codigo_telefone', ";
	$sql_alunos .= " telefone ='$telefone', codigo_celular = '$codigo_celular', celular='$celular', email='$email', ";
	$sql_alunos .= " identidade = '$identidade', orgao='$orgao', cpf = '$cpf', nascimento='$data_nascimento', ";
	$sql_alunos .= " endereco='$endereco', cep='$cep', bairro='$bairro', municipio='$municipio', ";
	$sql_alunos .= " observacoes='$observacoes' ";
	$sql_alunos .= " where registro='$registro'";
	// echo $sql_alunos . "<br>";
	
	$resultado_insere = $db->Execute($sql_alunos);
	if($resultado_insere === false) die ("Nao foi possivel atualizar o registro na tabela alunos");

	// echo "ORIGEM: " . $origem . "<br>";
	/* Quando atualiza volta para ver_cada.php menos no caso de ter sido chamado desde listar.php */
	header("Location: cadastro_dae.php?registro=$registro");
	
	exit;    

}

// Aluno
$sql  = "select registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, ";
$sql .= "endereco, cep, bairro, municipio, observacoes from alunos where registro='$registro'";
// echo $sql . "<br>";

if($debug == 1)
    echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Nao foi possivel consultar a tabela alunos");
// Verifico se o aluno está cadastrado
$quantidade = $resultado->RecordCount();
if ($quantidade == 0) {
	echo "Aluno não cadastrado como estagiário. Tem certeza que está cursando estágio? <br />";
	echo "Favor enviar um e-mail para <a href='mailto:estagio@ess.ufrj.br'>estagio@ess.ufrj.br</a>";
	exit;
}

while(!$resultado->EOF) {
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
	$dataCorrigida = split("/",$nova_data);
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
$smarty->assign("observacoes",$observacoes);

$smarty->display("alunos-atualizar_atualiza_dae.tpl");

exit;

?>