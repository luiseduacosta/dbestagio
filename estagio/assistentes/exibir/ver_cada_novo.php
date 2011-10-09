<?php

include_once("../../setup.php");
include_once("../../autentica.inc");

$indice = $_REQUEST['indice'];
$id_supervisor = $_REQUEST['id_supervisor'];
// echo "indice " . $indice . "<br />";
// echo "id_supervisor recebido: " . $id_supervisor . "<br />";

// $sql = "select id from supervisores";

$sql  = "select supervisores.id, supervisores.cress, supervisores.nome, supervisores.telefone, supervisores.celular, supervisores.email, "; 
$sql .= " estagio.id as id_estagio, estagio.instituicao, ";
$sql .= " supervisores.observacoes ";
$sql .= " from supervisores ";
$sql .= " inner join inst_super on supervisores.id = inst_super.id_supervisor ";
$sql .= " inner join estagio on inst_super.id_instituicao = estagio.id ";
$sql .= " order by nome, id_supervisor";
// echo $sql . "<br>";
$resultado_total = $db->Execute($sql);
$ultimo = $resultado_total->RecordCount();
// echo $ultimo . "<br>";

// Se estou no final o proximo registro e o primeiro
if ($indice >= $ultimo) {
	$indice = 0;
}

// Se estou no primerio registro o registro anterior e o ultimo
if ($indice < 0) {
	$indice = $ultimo-1;
}

// Calculo o indice
if (!empty($id_supervisor)) {
		// $sql = "select id from supervisores order by nome, id";
/*
		$sql  = "select supervisores.id as num_supervisor, supervisores.cress, supervisores.nome, supervisores.telefone, supervisores.celular, supervisores.email, "; 
		$sql .= " estagio.id, estagio.instituicao, ";
		$sql .= " supervisores.observacoes ";
		$sql .= " from supervisores ";
		$sql .= " left outer join inst_super on supervisores.id = inst_super.id_supervisor ";
		$sql .= " left outer join estagio on inst_super.id_instituicao = estagio.id ";
		$sql .= " order by nome, id_supervisor";
*/
		// echo $sql . "<br />";
		$resultado = $db->Execute($sql);
		// echo "Empty " . "<br>" ;
		$i = 0;
		// echo $i . "<br />";
		while (!$resultado->EOF) {
			$num_supervisor  = $resultado->fields['id'];
			$nome_supervisor = $resultado->fields['nome'];
			$id_instituicao  = $resultado->fields['id_estagio'];
			// echo "Id instituicao: ". $id_instituicao . "<br>";
			// echo "id_supervisor -> " . $id_supervisor . " num_supervisor -> " . $num_supervisor . " Nome: "  . $nome_supervisor . "<br />";
			if ($num_supervisor == $id_supervisor) {
				$indice = $i;
				// echo "Indice " . $indice . " id_supervisor " . $id_supervisor . "<br />";
				// break;
			}
			$i++;
			$resultado->MoveNext();
		}
}

// Rotina para acrescentar uma instituicao
if (!empty($_POST['num_instituicao'])) {
	// echo "Acrescentar instituicao<br>";
	$sql = "insert into inst_super (id_supervisor,id_instituicao) values('$id_supervisor','$_POST[num_instituicao]')";
	// echo $sql . "<br>";
	$resultado = $db->Execute($sql);
	if ($resultado === false) die ("Não foi possível inserir dados na tabela inst_super");	
} else {
	// echo "Nada: " . $_POST[num_instituicao] . "<br>";
}

$sql  = "select supervisores.id as id_supervisor, supervisores.cress, supervisores.nome, supervisores.telefone, supervisores.celular, email, "; 
$sql .= " estagio.id, estagio.instituicao, ";
$sql .= " supervisores.observacoes ";
$sql .= " from supervisores ";
$sql .= " left outer join inst_super on supervisores.id = inst_super.id_supervisor ";
$sql .= " left outer join estagio on inst_super.id_instituicao = estagio.id ";
$sql .= " order by nome, id_supervisor";

// echo $sql . "<br>";
// echo "Indice: " . $indice . "<br>";
if (!isset($indice)) {
	echo "<meta http-equiv='refresh' content='1;url=listar_todos.php?ordem=instituicao' />";
	die ("Nao foi encontrado o índice");
}

$resultado = $db->SelectLimit($sql,1,$indice);
if ($resultado === false) die ("1 Não foi possível consultar a tabela supervisores");
while (!$resultado->EOF) {
	$id_supervisor = $resultado->fields['id'];
	// echo "id " . $id_supervisor = $resultado->fields['id'];
	$cress = $resultado->fields['cress'];
	$nome = $resultado->fields['nome'];
	$telefone = $resultado->fields['telefone'];
	$celular = $resultado->fields['celular'];
	$email = $resultado->fields['email'];
	$id_instituicao = $resultado->fields['id_estagio'];
	// $instituicao = $resultado->fields['instituicao'];
	$observacoes = $resultado->fields['observacoes'];

	// Capturo as instituicoes campo de emprego do supervisor
	$sql_instituicoes = "select estagio.id, estagio.instituicao from estagio "; 
	$sql_instituicoes .= " inner join inst_super on estagio.id=inst_super.id_instituicao ";
	$sql_instituicoes .= "where inst_super.id_supervisor='$id_supervisor'";
	// echo $sql_instituicoes . "<br>";
	$resultado = $db->Execute($sql_instituicoes);
	if ($resultado === false) die ("Não foi possível consultar a tabela estagio");
	$i = 0;
	while (!$resultado->EOF) {
		$inst_emprego[$i]['id_instituicao'] = $resultado->fields['id'];
		$inst_emprego[$i]['instituicao'] = $resultado->fields['instituicao'];
		// echo "Instituicao: " . $inst_estagio[$i]['instituicao'] . "<br>";
		$i++;
		$resultado->MoveNext();
	}

	// Alunos supervisionados pelo supervisor
	$sqlalunos  = "select alunos.id, alunos.registro, alunos.nome, estagiarios.periodo, estagiarios.id_instituicao from alunos ";
	$sqlalunos .= " inner join estagiarios on estagiarios.registro = alunos.registro ";
	$sqlalunos .= " where estagiarios.id_supervisor = $id_supervisor";
	$sqlalunos .= " order by estagiarios.periodo, alunos.nome";
	// echo "Alunos: " . $sqlalunos . "<br>";

	$res_alunos = $db->Execute($sqlalunos);
	if ($res_alunos === false) die ("Não foi possível consultar a tabela alunos");
	$i = 0;
	while (!$res_alunos->EOF) {
		$alunos[$i]['id_aluno'] = $res_alunos->fields['id'];
		$alunos[$i]['registro'] = $res_alunos->fields['registro'];
		$alunos[$i]['nome'] = $res_alunos->fields['nome'];
		$alunos[$i]['periodo'] = $res_alunos->fields['periodo'];
		$alunos[$i]['id_instituicao'] = $res_alunos->fields['id_instituicao'];

		$id_instituicao = $res_alunos->fields['id_instituicao'];
		$sql_aluno_instituicao = "select instituicao from estagio where id = $id_instituicao";
		// echo $sql_aluno_instituicao . "<br>";
		$res_aluno_instituicao = $db->Execute($sql_aluno_instituicao);

		$alunos[$i]['instituicao'] = $res_aluno_instituicao->fields['instituicao'];
		// echo $res_aluno_instituicao->fields['instituicao'];
		
		$i++;
		// echo $i;
		$res_alunos->MoveNext();
	}

	if (!empty($cress)) {
		$sqlcurso = "select id from curso_inscricao_supervisor where cress=$cress";
		// echo $sqlcurso . "<br />";
		$supervisores_curso = $db->Execute($sqlcurso);
		if ($supervisores_curso === false) die ("Não foi possível consultar a tabela curso_inscricao_supervisores");
		$id_curso = $supervisores_curso->fields['id'];
		// echo "Id curso: " . $id_curso . "<br>";
	}
	
	$resultado->MoveNext();
}

// Instituicoes
$sql = "select id, instituicao from estagio order by instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela estagio");
$i = 0;
while (!$resultado->EOF) {
    $instituicoes[$i]['id_instituicao'] = $resultado->fields['id'];
    $instituicoes[$i]['instituicao'] = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("ultimo",$ultimo-1);
$smarty->assign("indice",$indice);
$smarty->assign("id_supervisor",$id_supervisor);
$smarty->assign("cress",$cress);
$smarty->assign("nome",$nome);
$smarty->assign("telefone",$telefone);
$smarty->assign("celular",$celular);
$smarty->assign("email",$email);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("emprego",$inst_emprego);
$smarty->assign("id_curso",$id_curso);
$smarty->assign("observacoes",$observacoes);
$smarty->assign("alunos",$alunos);
$smarty->assign("instituicoes",$instituicoes);
$smarty->display("supervisores_ver_cada.tpl");

$db->Close();

exit;

?>
