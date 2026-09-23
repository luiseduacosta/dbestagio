<?php

include_once("../../autentica.inc");

$indice = $_REQUEST['indice'];
$supervisor_id = $_REQUEST['supervisor_id'];
// echo "indice " . $indice . "<br />";
// echo "id_supervisor recebido: " . $id_supervisor . "<br />";

// $sql = "select id from supervisores";

$sql  = "select supervisores.id, supervisores.cress, supervisores.nome, supervisores.telefone, supervisores.celular, supervisores.email, "; 
$sql .= " instituicoes.id, instituicoes.instituicao, ";
$sql .= " supervisores.observacoes ";
$sql .= " from supervisores ";
$sql .= " inner join inst_super on supervisores.id = inst_super.supervisor_id ";
$sql .= " inner join instituicoes on inst_super.instituicao_id = instituicoes.id ";
$sql .= " order by nome, inst_super.supervisor_id";
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
if (!empty($supervisor_id)) {
		// $sql = "select id from supervisores order by nome, id";
/*
		$sql  = "select supervisores.id as num_supervisor, supervisores.cress, supervisores.nome, supervisores.telefone, supervisores.celular, supervisores.email, "; 
		$sql .= " instituicoes.id, instituicoes.instituicao, ";
		$sql .= " supervisores.observacoes ";
		$sql .= " from supervisores ";
		$sql .= " left outer join inst_super on supervisores.id = inst_super.supervisor_id ";
		$sql .= " left outer join instituicoes on inst_super.instituicao_id = instituicoes.id ";
		$sql .= " order by nome, inst_super.supervisor_id";
*/
		// echo $sql . "<br />";
		$resultado = $db->Execute($sql);
		// echo "Empty " . "<br>" ;
		$i = 0;
		// echo $i . "<br />";
		while (!$resultado->EOF) {
			$num_supervisor  = $resultado->fields['id'];
			$nome_supervisor = $resultado->fields['nome'];
			$instituicao_id  = $resultado->fields['instituicao_id'];
			// echo "Id instituicao: ". $id_instituicao . "<br>";
			// echo "id_supervisor -> " . $id_supervisor . " num_supervisor -> " . $num_supervisor . " Nome: "  . $nome_supervisor . "<br />";
			if ($num_supervisor == $supervisor_id) {
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
	$sql = "insert into inst_super (supervisor_id,instituicao_id) values('$supervisor_id','$_POST[num_instituicao]')";
	// echo $sql . "<br>";
	$resultado = $db->Execute($sql);
	if ($resultado === false) die ("Não foi possível inserir dados na tabela inst_super");	
} else {
	// echo "Nada: " . $_POST[num_instituicao] . "<br>";
}

$sql  = "select supervisores.id, supervisores.cress, supervisores.nome, supervisores.telefone, supervisores.celular, email, "; 
$sql .= " instituicoes.id, instituicoes.instituicao, ";
$sql .= " supervisores.observacoes ";
$sql .= " from supervisores ";
$sql .= " left outer join inst_super on supervisores.id = inst_super.supervisor_id ";
$sql .= " left outer join instituicoes on inst_super.instituicao_id = instituicoes.id ";
$sql .= " order by nome, inst_super.supervisor_id";

// echo $sql . "<br>";
// echo "Indice: " . $indice . "<br>";
if (!isset($indice)) {
	echo "<meta http-equiv='refresh' content='1;url=listar_todos.php?ordem=instituicao' />";
	die ("Nao foi encontrado o índice");
}

$resultado = $db->SelectLimit($sql,1,$indice);
if ($resultado === false) die ("1 Não foi possível consultar a tabela supervisores");
while (!$resultado->EOF) {
	$supervisor_id = $resultado->fields['id'];
	// echo "id " . $supervisor_id = $resultado->fields['id'];
	$cress = $resultado->fields['cress'];
	$nome = $resultado->fields['nome'];
	$telefone = $resultado->fields['telefone'];
	$celular = $resultado->fields['celular'];
	$email = $resultado->fields['email'];
	$instituicao_id = $resultado->fields['instituicao_id'];
	$observacoes = $resultado->fields['observacoes'];

	// Capturo as instituicoes campo de emprego do supervisor
	$sql_instituicoes = "select instituicoes.id, instituicoes.instituicao from instituicoes ";
	$sql_instituicoes .= " inner join inst_super on instituicoes.id=inst_super.instituicao_id ";
	$sql_instituicoes .= "where inst_super.supervisor_id='$supervisor_id'";
	// echo $sql_instituicoes . "<br>";
	$resultado = $db->Execute($sql_instituicoes);
	if ($resultado === false) die ("Não foi possível consultar a tabela instituicoes");
	$i = 0;
	while (!$resultado->EOF) {
		$inst_emprego[$i]['instituicao_id'] = $resultado->fields['id'];
		$inst_emprego[$i]['instituicao'] = $resultado->fields['instituicao'];
		// echo "Instituicao: " . $inst_estagio[$i]['instituicao'] . "<br>";
		$i++;
		$resultado->MoveNext();
	}

	// Alunos supervisionados pelo supervisor
	$sqlalunos .= "select alunos.id, alunos.registro, alunos.nome, estagiarios.periodo, estagiarios.instituicao_id as id_instituicao from alunos ";
	$sqlalunos .= " inner join estagiarios on estagiarios.registro = alunos.registro ";
	$sqlalunos .= " where estagiarios.supervisor_id = $supervisor_id";
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
		$alunos[$i]['instituicao_id'] = $res_alunos->fields['instituicao_id'];

		$instituicao_id = $res_alunos->fields['instituicao_id'];
		$sql_aluno_instituicao = "select instituicao from instituicoes where id = $instituicao_id";
		// echo $sql_aluno_instituicao . "<br>";
		$res_aluno_instituicao = $db->Execute($sql_aluno_instituicao);

		$alunos[$i]['instituicao'] = $res_aluno_instituicao->fields['instituicao'];
		// echo $res_aluno_instituicao->fields['instituicao'];
		
		$i++;
		// echo $i;
		$res_alunos->MoveNext();
	}

	if (!empty($cress)) {
		if ($cress != 0) {
			$sqlcurso = "select id from curso_inscricao_supervisor where cress=$cress";
			// echo $sqlcurso . "<br />";
			$supervisores_curso = $db->Execute($sqlcurso);
			if ($supervisores_curso === false) die ("Não foi possível consultar a tabela curso_inscricao_supervisores");
			$id_curso = $supervisores_curso->fields['id'];
			// echo "Id curso: " . $id_curso . "<br>";
		}
	}

	$resultado->MoveNext();
}

// Instituicoes
$sql = "select id, instituicao from instituicoes order by instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela instituicoes");
$i = 0;
while (!$resultado->EOF) {
    $instituicoes[$i]['instituicao_id'] = $resultado->fields['id'];
    $instituicoes[$i]['instituicao'] = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("ultimo",$ultimo-1);
$smarty->assign("indice",$indice);
$smarty->assign("supervisor_id",$supervisor_id);
$smarty->assign("cress",$cress);
$smarty->assign("nome",$nome);
$smarty->assign("telefone",$telefone);
$smarty->assign("celular",$celular);
$smarty->assign("email",$email);
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("emprego",$inst_emprego);
$smarty->assign("id_curso",$id_curso);
$smarty->assign("observacoes",$observacoes);
$smarty->assign("alunos",$alunos);
$smarty->assign("instituicoes",$instituicoes);
$smarty->display("supervisores_ver_cada.tpl");

exit;

?>
