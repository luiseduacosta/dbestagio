<?php

include_once("../setup.php");

$submit = isset($_POST['submit']) ? $_POST['submit'] : NULL;

$nome            = $_POST['nome'];
$registro        = $_REQUEST['registro'];
$codigo_telefone = $_POST['codigo_telefone'];
$telefone        = $_POST['telefone'];
$codigo_celular  = $_POST['codigo_celular'];
$celular         = $_POST['celular'];
$email           = strtolower($_POST['email']);
$cpf             = $_POST['cpf'];
$identidade      = $_POST['identidade'];
$orgao           = $_POST['orgao']; 
$nascimento      = $_POST['nascimento'];
$endereco        = $_POST['endereco'];
$cep             = $_POST['cep'];
$municipio       = $_POST['municipio'];
$bairro          = $_POST['bairro'];

$id_instituicao = isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL;
$sql = "select instituicao from mural_estagio where id='$id_instituicao'";
// echo $sql. "<br>";
$resultado = $db->Execute($sql);
$instituicao = $resultado->fields['instituicao'];

// echo "Id instituicao " . $id_instituicao . "<br>";
// echo "Nascimento " . date("d-m-Y",strtotime($nascimento)) . "<br>";
// echo "Registro: " . $registro . "<br>";

// die;

if (empty($nascimento))
	$dataNascimento = "";
else
	$dataNascimento = date("Y-m-d",strtotime($nascimento));

if($submit) {
	// Verifico se ja existe um aluno com esse DRE
	$sql_verifica = "select nome from alunosNovos where registro = $registro";
	$resultado_sql_verifica = $db->Execute($sql_verifica);
	$quantidade = $resultado_sql_verifica->RecordCount();
	if ($quantidade > 0) {
		// echo "J� h� um aluno com esse n�mero de <a href='ver-aluno.php?id_aluno=$registro'>DRE</a>";
		header("Location:mural-alunos_modifica.php?registro=$registro");
		exit;
	}

	// Insero o registro na tabela alunosNovos
	$sql_alunos  = "insert into alunosNovos(registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, ";
	$sql_alunos .= "cpf, identidade, orgao, nascimento, endereco, cep, municipio, bairro) ";
	$sql_alunos .= "values('$registro','$nome','$codigo_telefone','$telefone','$codigo_celular','$celular','$email',";
	$sql_alunos .= "'$cpf','$identidade','$orgao','$dataNascimento','$endereco','$cep','$municipio','$bairro')";
	// echo $sql_alunos . "<br>";

	$resultado_insere = $db->Execute($sql_alunos);
    if($resultado_insere === false) die ("Não foi possível inserir o registro na tabela alunosNovos");

	// Pego o id do �ltimo registro inserido.
/*
    $res_ultimo = $db->Execute("select max(id) as ultimo_aluno from alunosNovos");
    if($res_ultimo === false) die ("Nao foi possivel consultar a sequencia alunosNovos");
    $ultimo_aluno = $res_ultimo->fields['ultimo_aluno'];
*/
	// Parece que ja nao e mais necessario

	// A data de hoje
	$data = date("Y-m-d");

	// Capturo o PERIODO_ATUAL
	$periodo = PERIODO_ATUAL;
	// echo $periodo . "<br>";

	// Insero um novo registro na tabela mural_inscricao com o numero de registro do aluno
	$sql_inserir = "insert into mural_inscricao (id_aluno,id_instituicao,data,periodo) ".
		"values('$registro','$id_instituicao','$data','$periodo')";
	// echo $sql_inserir . "<br>";
	$resultado_inscricao = $db->Execute($sql_inserir);
    if($resultado_inscricao === false) die ("Não foi possível inserir o registro na tabela mural_inscricao");

	header("Location:listaInscritos.php?id_instituicao=$id_instituicao");
	// header("Location:ver-mural.php?insere=$nome");

    exit;
}

// echo $registro . "<br>";

$smarty = new Smarty_estagio;
$smarty->assign("registro",$registro);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->display("../../mural/alunos-mural_insere.tpl");

exit;

?>
