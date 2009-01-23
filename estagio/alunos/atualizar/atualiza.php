<?php

include_once("../../autentica.inc");

include_once("../../db.inc");
include_once("../../setup.php");

$origem = $_REQUEST['origem'];
// echo $_SERVER['PHP_SELF'] . " " . $origem . "<br>";

// Se o programa foi chamado desde seleciona.php retorna a ele proprio
if (substr_count($origem,"seleciona.php") == 1) {
	$origem = $_SERVER['PHP_SELF'];	
} elseif (substr_count($origem,"listar_dae.php") == 1) {
	$origem = "http://web.intranet.ess.ufrj.br/estagio/alunos/exibir/listar_dae.php";
}

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
$observacoes     = $_REQUEST['observacoes'];

// echo $nascimento . "<br>";

if($debug == 1) {
    // print_r($_REQUEST) . "<br>";
	}

// Estagiarios
$id_estagiarios = $_POST['id_estagiarios'];
$periodo        = $_POST['periodo'];
$nivel          = $_POST['nivel'];
$turno          = $_POST['turno'];
$tc             = $_POST['tc'];
$id_instituicao = $_POST['id_instituicao'];
$id_area        = $_POST['id_area'];
$id_supervisor  = $_POST['id_supervisor'];
$id_professor   = $_POST['id_professor'];
$nota           = $_POST['nota'];
$ch             = $_POST['ch'];

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
if(($acao == 1) || ($cadastro == 1)) {
	// Atualiza somente tabela estagiarios
	if(!empty($id_estagiarios)) {
		$sql_estagiarios  = "update estagiarios set id_aluno='$id_aluno', registro='$registro', ";
		$sql_estagiarios .= " turno='$turno', nivel='$nivel', periodo='$periodo', tc='$tc', ";
		$sql_estagiarios .= " id_supervisor='$id_supervisor', id_instituicao='$id_instituicao', id_area='$id_area', id_professor='$id_professor', ";
		$sql_estagiarios .= " nota='$nota', ch='$ch' ";
		$sql_estagiarios .= " where id='$id_estagiarios'";
		// echo $sql_estagiarios . "<br>";
		$resultado_insere = $db->Execute($sql_estagiarios);
		if($resultado_insere === false) die ("Nao foi possivel atualizar o registro na tabela estagiarios");
	} else {
		// Atualiza somente tabela alunos

		// Para salvar tenho que utilizar o formato aaaa/mm/dd/
		$novoNascimento = split("/",$nascimento);
		$data_nascimento = $novoNascimento[2] . "-" . $novoNascimento[1] . "-" . $novoNascimento[0];
		
		$sql_alunos  = "update alunos set registro ='$registro', nome ='$nome', codigo_telefone ='$codigo_telefone', ";
		$sql_alunos .= " telefone ='$telefone', codigo_celular = '$codigo_celular', celular='$celular', email='$email', ";
		$sql_alunos .= " identidade = '$identidade', orgao = '$orgao', cpf = '$cpf', nascimento='$data_nascimento', ";
		$sql_alunos .= " endereco='$endereco', cep='$cep', bairro='$bairro', municipio='$municipio', ";
		$sql_alunos .= " observacoes='$observacoes' ";
		$sql_alunos .= " where id='$id_aluno'";
		// echo $sql_alunos . "<br>";
		$resultado_insere = $db->Execute($sql_alunos);
		if($resultado_insere === false) die ("Nao foi possivel atualizar o registro na tabela alunos");
		// Atualizo tambem o campo registro na tabela estagiarios
		$sql_registro = "update estagiarios set registro='$registro' where id_aluno='$id_aluno'";
		$resultado_registro = $db->Execute($sql_registro);
		if($resultado_registro === false) die ("Nao foi possivel atualizar o campo registro na tabela estagiarios");	
	}

	// echo "ORIGEM: " . $origem . "<br>";
	/* Quando atualiza volta para ver_cada.php menos no caso de ter sido chamado desde listar.php */
	$buscastring  = substr_count($origem,"listar.php");
	if (substr_count($origem,"listar.php") == 1) {
		header("Location: $origem");
	} else {
		header("Location: ../exibir/ver_cada.php?id_aluno=$id_aluno");
	}
	exit;
}

// Aluno
$sql  = "select registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, ";
$sql .= "endereco, cep, bairro, municipio, observacoes from alunos where id='$id_aluno'";

if($debug == 1)
    echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Nao foi possivel consultar a tabela alunos");
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

// Estagiarios
$sql_estagiarios = "select * from estagiarios where id_aluno=$id_aluno order by periodo";
// echo $sql_estagiarios . "<br>";
$resultado_estagiario = $db->Execute($sql_estagiarios);
if($resultado_estagiario === false) die ("Nao foi possivel consultar a tabela estagiarios");
$i = 0;
while(!$resultado_estagiario->EOF) {
	$estagiarios[$i]['id']             = $resultado_estagiario->fields["id"];
	$estagiarios[$i]['tc']	       	   = $resultado_estagiario->fields["tc"];
	$estagiarios[$i]['periodo']        = $resultado_estagiario->fields["periodo"];
	$estagiarios[$i]['turno']          = $resultado_estagiario->fields["turno"];
	$estagiarios[$i]['nivel']          = $resultado_estagiario->fields["nivel"];
	$estagiarios[$i]['id_instituicao'] = $resultado_estagiario->fields["id_instituicao"];
	$estagiarios[$i]['id_supervisor']  = $resultado_estagiario->fields["id_supervisor"];
	$estagiarios[$i]['id_professor']   = $resultado_estagiario->fields["id_professor"];
	$estagiarios[$i]['id_area']        = $resultado_estagiario->fields["id_area"];
	$estagiarios[$i]['nota']           = $resultado_estagiario->fields["nota"];
	$estagiarios[$i]['ch']	           = $resultado_estagiario->fields["ch"];

	$id_instituicao = $resultado_estagiario->fields["id_instituicao"];
	$id_supervisor  = $resultado_estagiario->fields["id_supervisor"];
	$id_professor   = $resultado_estagiario->fields["id_professor"];
	$id_area        = $resultado_estagiario->fields["id_area"];

	// Instituicao
	if(!empty($id_instituicao)) {
		$sql_instituicao = "select id, instituicao from estagio where id=$id_instituicao";
		$resultado_instituicao = $db->Execute($sql_instituicao);
		if($resultado_instituicao === false) die ("Nao foi possivel consultar a tabela estagio");
		while(!$resultado_instituicao->EOF) {
			$estagiarios[$i]['instituicao'] = $resultado_instituicao->fields["instituicao"];
			$resultado_instituicao->MoveNext();
		}
	} else {
		$id_instituicao = 0;
		$estagiarios[$i]['instituicao'] = "Sem dados";
	}

	// Supervisor
	if(!empty($id_supervisor)) {
		$sql_nome_supervisor = "select nome from supervisores where id=$id_supervisor";
		$resultado_nome_supervisor = $db->Execute($sql_nome_supervisor);
		if($resultado_nome_supervisor === false) die ("Nao foi possivel consultar a tabela supervisores");
		while(!$resultado_nome_supervisor->EOF)	{
			$estagiarios[$i]['supervisor'] = $resultado_nome_supervisor->fields["nome"];
			$resultado_nome_supervisor->MoveNext();
		}
	} else {
		$id_supervisor = 0;
		$estagiarios[$i]['supervisor'] = "Sem dados";
	}

	// Professor
	if(!empty($id_professor)) {
		$sql_nome_professor = "select nome from professores where id=$id_professor";
		$resultado_nome_professor = $db->Execute($sql_nome_professor);
		if($resultado_nome_professor === false) die ("Nao foi possivel consultar a tabela professores");
		while(!$resultado_nome_professor->EOF) {
			$estagiarios[$i]['professor'] = $resultado_nome_professor->fields["nome"];
			$resultado_nome_professor->MoveNext();
		}
	} else {
		$id_professor = 0;
		$estagiarios[$i]['professor'] = "Sem dados";
	}

	// Area
	if(!empty($id_area)) {
		$sql_nome_area = "select area from areas_estagio where id=$id_area";
		$resultado_nome_area = $db->Execute($sql_nome_area);
		if($resultado_nome_area === false) die ("Nao foi possivel consultar a tabela areas_estagio");
		while(!$resultado_nome_area->EOF) {
			$estagiarios[$i]['area'] = $resultado_nome_area->fields["area"];
			$resultado_nome_area->MoveNext();
		}
	} else {
		$id_area = 0;
		$estagiarios[$i]['area'] = "Sem dados";
	}

	$resultado_estagiario->MoveNext();
	$i++;
}

// Capturo a informacao sobre as instituicoes
$sql = "select id, instituicao from estagio order by instituicao";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Nao foi possivel consultar a tabela estagio");
$i = 0;
while(!$resultado->EOF) {
    $instituicoes[$i]['id_instituicao'] = $resultado->fields['id'];
    $instituicoes[$i]['instituicao']    = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

// Capturo a informacao sobre os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$resultado_supervisores = $db->Execute($sql_supervisores);
if($resultado_supervisores === false) die ("Nao foi possivel consultar a tabela supervisores");
$i = 0;
while(!$resultado_supervisores->EOF) {
    $supervisores[$i]['id_supervisor'] = $resultado_supervisores->fields['id'];
    $supervisores[$i]['supervisor']    = $resultado_supervisores->fields['nome'];
    $resultado_supervisores->MoveNext();
    $i++;
}

// Capturo a informacao sobre os professores
$sql_professores = "select id, nome from professores order by nome";
$resultado_professores = $db->Execute($sql_professores);
if($resultado_professores === false) die ("Nao foi possivel consultar a tabela professores");
$i = 0;
while(!$resultado_professores->EOF) {
    $professores[$i]['id_professor'] = $resultado_professores->fields['id'];
    $professores[$i]['professor']    = $resultado_professores->fields['nome'];
    $resultado_professores->MoveNext();
    $i++;
}

// Capturo a informacao sobre as areas
$sql_areas = "select id, area from areas_estagio order by area";
$resultado_areas = $db->Execute($sql_areas);
if($resultado_areas === false) die ("Nao foi possivel consultar a tabela areas_estagio");
$i = 0;
while(!$resultado_areas->EOF) {
    $areas[$i]['id_area'] = $resultado_areas->fields['id'];
    $areas[$i]['area']    = $resultado_areas->fields['area'];
    $resultado_areas->MoveNext();
    $i++;
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
// Estagios
$smarty->assign("estagiarios",$estagiarios);
// Instituicoes
$smarty->assign("instituicoes",$instituicoes);
// Supervisores
$smarty->assign("supervisores",$supervisores);
// Professores
$smarty->assign("professores",$professores);
// Areas
$smarty->assign("areas",$areas);

$smarty->display("alunos-atualizar_atualiza.tpl");

exit;

?>