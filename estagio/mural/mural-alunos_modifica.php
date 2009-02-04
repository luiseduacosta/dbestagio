<?php

include_once("../autoriza.inc");

include_once("../db.inc");
include_once("../setup.php");

$id_aluno = isset($_REQUEST['id_aluno']) ? (int)$_REQUEST['id_aluno'] : NULL;
$registro = isset($_REQUEST['registro']) ? (int)$_REQUEST['registro'] : NULL;
$id_instituicao = isset($_REQUEST['id_instituicao']) ? (int)$_REQUEST['id_instituicao'] : NULL;
$aluno = isset($_REQUEST['aluno']) ? $_REQUEST['aluno'] : NULL;

// echo "Dados recebidos para atualização: " . $id_aluno . " " . $registro . " " . $instituicao . " " . $num_instituicao .  " ". $aluno . "<br>";
// die;

$sql = "select instituicao from mural_estagio where id='$id_instituicao'";

$resultado = $db->Execute($sql);
$instituicao = $resultado->fields['instituicao'];

// Se aluno = 0 aluno eh novo, se aluno = 1 aluno já conhecido
/*
if ($aluno == 0) {
		echo "Aluno novo";
} elseif ($aluno == 1) {
		echo "Aluno já conhecido";
}
*/

// Se não eh um aluno "novo" busco os estágios já cursados
if ($aluno == 1) {
		// Pego esta informação para fazer a tabela dos anteriores estágios
		$sql  = "SELECT estagiarios.id, estagiarios.periodo, estagiarios.nivel, estagiarios.turno, " .
		"estagio.instituicao, supervisores.nome " .
		"FROM estagiarios " .
		"right outer join estagio " .
		"on estagiarios.id_instituicao = estagio.id " .
		"right outer join supervisores " .
		"on estagiarios.id_supervisor = supervisores.id " .
//		"where estagiarios.id_aluno = $id_aluno " .
		"where estagiarios.registro = $registro " .
		"order by estagiarios.periodo";
		// echo $sql . "<br>";
		$resultado = $db->Execute($sql);
		if($resultado === false) die ("Não foi possível consultar as tabelas estagiarios, estagio, supervisores");
		$i = 0;
		while(!$resultado->EOF) {
				$estagiarios[$i]['id']          = $resultado->fields['id'];
				$estagiarios[$i]['periodo']     = $resultado->fields['periodo'];
				$estagiarios[$i]['nivel']       = $resultado->fields['nivel'];
				$estagiarios[$i]['turno']       = $resultado->fields['turno'];
				$estagiarios[$i]['instituicao'] = $resultado->fields['instituicao'];
				$estagiarios[$i]['supervisor']  = $resultado->fields['nome'];

				$resultado->MoveNext();
				$i++;
		}

		// Capturo a informação sobre o aluno
		// $sql_alunos = "select id, registro, nome, telefone, celular, email from alunos where registro='$registro'";
		$sql_alunos = "select id, registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, endereco, cep, municipio, bairro, observacoes from alunos where registro='$registro'";
} elseif ($aluno == 0) {
		$sql_alunos = "select id, registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, endereco, cep, municipio, bairro, observacoes from alunosNovos where registro='$registro'";
		// echo $sql_alunos . "<br>";
}

// echo $sql_alunos . "<br>";

$resultado_alunos = $db->Execute($sql_alunos);
if($resultado_alunos === false) die ("Não foi possível consultar a tabela alunos ou alunosNovos");
while(!$resultado_alunos->EOF) {
    $aluno_id              = $resultado_alunos->fields['id'];
    $aluno_registro        = $resultado_alunos->fields['registro'];
    $aluno_nome            = $resultado_alunos->fields['nome'];
	$aluno_codigo_telefone = $resultado_alunos->fields['codigo_telefone'];
    $aluno_telefone        = $resultado_alunos->fields['telefone'];
	$aluno_codigo_celular  = $resultado_alunos->fields['codigo_celular'];
    $aluno_celular         = $resultado_alunos->fields['celular'];
    $aluno_email           = strtolower($resultado_alunos->fields['email']);
	$aluno_cpf             = $resultado_alunos->fields['cpf'];
	$aluno_identidade      = $resultado_alunos->fields['identidade'];
	$aluno_orgao           = $resultado_alunos->fields['orgao'];
	$aluno_nascimento      = $resultado_alunos->fields['nascimento'];

	// Transformo a data do BD de aaaa-mm-dd para dd-mm-aaaa
	$nascimento = $resultado_alunos->fields['nascimento'];
	// echo $nascimento . "<br>";
	if ($nascimento == 0)
		$aluno_nascimento = "";
	else
		$aluno_nascimento = date("d-m-Y",strtotime($nascimento));
	// echo $aluno_nascimento . "<br>";

	$aluno_endereco    = $resultado_alunos->fields['endereco'];
	$aluno_cep         = $resultado_alunos->fields['cep'];
	$aluno_municipio   = $resultado_alunos->fields['municipio'];
	$aluno_bairro      = $resultado_alunos->fields['bairro'];
	$aluno_observacoes = $resultado_alunos->fields['observacoes'];

    $resultado_alunos->MoveNext();
}

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("mural_autentica",$mural_autentica);

// Tabela de estagios anteriores
$smarty->assign("estagiarios",$estagiarios);
// Tabela inserir novo estágio
$smarty->assign("aluno",$aluno);

$smarty->assign("id_aluno",$id_aluno);
$smarty->assign("num_aluno",$num_aluno);

$smarty->assign("registro",$registro);
$smarty->assign("aluno_nome",$aluno_nome);
$smarty->assign("codigo_telefone",$aluno_codigo_telefone);
$smarty->assign("telefone",$aluno_telefone);
$smarty->assign("codigo_celular",$aluno_codigo_celular);
$smarty->assign("celular",$aluno_celular);
$smarty->assign("email",$aluno_email);
$smarty->assign("cpf",$aluno_cpf);
$smarty->assign("identidade",$aluno_identidade);
$smarty->assign("orgao",$aluno_orgao);
$smarty->assign("nascimento",$aluno_nascimento);
$smarty->assign("endereco",$aluno_endereco);
$smarty->assign("cep",$aluno_cep);
$smarty->assign("municipio",$aluno_municipio);
$smarty->assign("bairro",$aluno_bairro);
$smarty->assign("observacoes",$aluno_observacoes);

$smarty->assign("instituicao",$instituicao);
$smarty->assign("id_instituicao",$id_instituicao);

$smarty->display("mural-alunos_modifica.tpl");

exit;

?>