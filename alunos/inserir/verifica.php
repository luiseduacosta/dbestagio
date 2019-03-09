<?php

if ($debug == 1) {
	echo $_SERVER['PHP_SELF'] . "<br>";
}

if (empty($origem)) {
    $origem = $_SERVER['HTTP_REFERER'];
}

// echo $origem . "<br>";

include_once("../../setup.php");
include_once("../../autentica.inc");

$registro = $_REQUEST['registro'];

// Busco o registro entre os alunos
$sqlAlunos  = "select id, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, ";
$sqlAlunos .= "endereco, cep, bairro, municipio from alunos where registro='$registro'";
// echo $sqlAlunos . "<br>";

// $resultado0 = $db->Execute($sql0);
$resultado0 = $db->Execute($sqlAlunos);
if ($resultado0 === false) die ("Não foi possível consultar a tabela alunos");
$quantidade = $resultado0->RecordCount();

// echo "Quantidade de alunos na tabela alunos: " . $quantidade . "<br>";
// Se nao esta na tabela alunos tenho que verificar se esta na tabela alunosNovos
if ($quantidade == 0) {
	// Busco o aluno entre os alunos da tabela alunosNovos
	// $sqlAlunos = "select registro from alunosNovos where registro='$registro'";
	$sqlAlunos  = "select nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, ";
	$sqlAlunos .= "endereco, cep, bairro, municipio from alunosNovos where registro='$registro'";
	// echo $sqlAlunos . "<br>";
	$resultadoAlunos = $db->Execute($sqlAlunos);
	if ($resultadoAlunos === false) die ("Não foi possível consultar a tabela alunosNovos");
	$quantidadeAlunos = $resultadoAlunos->RecordCount();
	// echo "Quantidade de alunos na tabela alunosNovos: " . $quantidadeAlunos  . "<br>";
	if ($quantidadeAlunos == 0) {
		// echo "Aluno nao cadastrado nem em alunosNovos nem em alunos";
	} else {
		// echo "Aluno cadastrado na tabela alunosNovos " . "<br>";
		// $sqlAlunos  = "select nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, nascimento, ";
		// $sqlAlunos .= "endereco, cep, bairro, municipio from alunosNovos where registro='$registro'";
		$resultado = $db->Execute($sqlAlunos);
		while (!$resultado->EOF) {
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
			$data_sql = date('d/m/Y', strtotime($nascimento));

			$endereco = $resultado->fields['endereco'];
			$cep = $resultado->fields['cep'];
			$bairro = $resultado->fields['bairro'];
			$municipio = $resultado->fields['municipio'];
			$cadastro = 0; // Aluno nao cadastrado na tabela alunos
			$resultado->MoveNext();
		}
	}
	// Se esta cadastrado na tabela alunos
} elseif ($quantidade > 0) {
	// echo "Aluno cadastrado na tabela alunos " . "<br>";
	header("Location:../exibir/ver_cada.php?registro=$registro");
	exit;
}

$smarty = new Smarty_estagio;
$smarty->assign("origem",$origem);
$smarty->assign("id_aluno",$id_aluno);
$smarty->assign("registro",$registro);
$smarty->assign("nome",$nome);
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
$smarty->assign("cadastro",$cadastro);
$smarty->display("alunos-inserir_verifica.tpl");

exit;

?>
