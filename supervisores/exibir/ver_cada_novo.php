<?php

include_once("../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");

$indice        = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;
$supervisor_id = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
// echo "indice " . $indice . "<br />";
// echo "id_supervisor recebido: " . $id_supervisor . "<br />";

// $sql = "select id from supervisores";

$sql  = "select supervisores.id as supervisor_id, supervisores.cress, supervisores.nome, supervisores.telefone, supervisores.celular, supervisores.email, "; 
$sql .= " instituicoes.id as instituicao_id, instituicoes.instituicao, ";
$sql .= " supervisores.observacoes ";
$sql .= " from supervisores ";
$sql .= " left outer join inst_super on supervisores.id = inst_super.supervisor_id ";
$sql .= " left outer join instituicoes on inst_super.instituicao_id = instituicoes.id ";
$sql .= " order by nome, inst_super.supervisor_id";
// echo $sql . "<br>";
$resultado_total = Supervisor::query($sql);
$ultimo = count($resultado_total);
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
		$resultado = Supervisor::query($sql);
		// echo "Empty " . "<br>" ;
		$i = 0;
		// echo $i . "<br />";
		foreach ($resultado as $item) {
			$num_supervisor  = (int)$item->supervisor_id;
			$nome_supervisor = $item->nome;
			// echo "id_supervisor -> " . $supervisor_id . " num_supervisor -> " . $num_supervisor . " Nome: "  . $nome_supervisor . "<br />";
			if ($num_supervisor == $supervisor_id) {
				$indice = $i;
				// echo "Indice " . $indice . " id_supervisor " . $supervisor_id . "<br />";
				// break;
			}
			$i++;
		}
}

// Rotina para acrescentar uma instituicao
if (!empty($_POST['num_instituicao'])) {
	// echo "Acrescentar instituicao<br>";
	$num_instituicao = (int)$_POST['num_instituicao'];
	if ($num_instituicao > 0) {
		$sql = "insert into inst_super (supervisor_id,instituicao_id) values(?, ?)";
		// echo $sql . "<br>";
		$resultado = Instituicao::query($sql, array($supervisor_id, $num_instituicao));
		if ($resultado === false) die ("Não foi possível inserir dados na tabela inst_super");
	}	
} else {
	// echo "Nada: " . $_POST[num_instituicao] . "<br>";
}

// Redefine $sql: o INSERT acima reaproveitou esta variavel. A consulta e a
// mesma de cima, portanto $ultimo (navegacao) e $resultado (exibicao) batem.
$sql  = "select supervisores.id as supervisor_id, supervisores.cress, supervisores.nome, supervisores.telefone, supervisores.celular, supervisores.email, "; 
$sql .= " instituicoes.id as instituicao_id, instituicoes.instituicao, ";
$sql .= " supervisores.observacoes ";
$sql .= " from supervisores ";
$sql .= " left outer join inst_super on supervisores.id = inst_super.supervisor_id ";
$sql .= " left outer join instituicoes on inst_super.instituicao_id = instituicoes.id ";
$sql .= " order by nome, inst_super.supervisor_id";

// echo $sql . "<br>";
// echo "Indice: " . $indice . "<br>";
if (!isset($indice)) {
	echo "<meta http-equiv='refresh' content='1;url=listar.php?ordem=instituicao' />";
	die ("Nao foi encontrado o índice");
}

$cress = $nome = $telefone = $celular = $email = $instituicao_id = $observacoes = '';
$resultado = Supervisor::query($sql);
if ($resultado === false || empty($resultado[$indice])) die ("1 Não foi possível consultar a tabela supervisores");
$item = $resultado[$indice];
$supervisor_id = (int)$item->supervisor_id;
// echo "id " . $supervisor_id = $resultado->fields['id'];
$cress = $item->cress;
$nome = $item->nome;
$telefone = $item->telefone;
$celular = $item->celular;
$email = $item->email;
$instituicao_id = (int)$item->instituicao_id;
$observacoes = $item->observacoes;

// Capturo as instituicoes campo de emprego do supervisor
$sql_instituicoes = "select instituicoes.id, instituicoes.instituicao from instituicoes ";
$sql_instituicoes .= " inner join inst_super on instituicoes.id=inst_super.instituicao_id ";
$sql_instituicoes .= "where inst_super.supervisor_id=?";
// echo $sql_instituicoes . "<br>";
$resultado = Instituicao::query($sql_instituicoes, array($supervisor_id));
if ($resultado === false) die ("Não foi possível consultar a tabela instituicoes");
$i = 0;
$inst_emprego = array();
foreach ($resultado as $item) {
	$inst_emprego[$i]['instituicao_id'] = (int)$item->id;
	$inst_emprego[$i]['instituicao'] = $item->instituicao;
	// echo "Instituicao: " . $inst_estagio[$i]['instituicao'] . "<br>";
	$i++;
}

// Alunos supervisionados pelo supervisor - consulta única (evita N+1)
$sqlalunos  = "select a.id, a.registro, a.nome, e.periodo, e.instituicao_id, i.instituicao from alunos a ";
$sqlalunos .= " inner join estagiarios e on e.registro = a.registro ";
$sqlalunos .= " left join instituicoes i on i.id = e.instituicao_id ";
$sqlalunos .= " where e.supervisor_id = ? ";
$sqlalunos .= " order by e.periodo, a.nome";
// echo "Alunos: " . $sqlalunos . "<br>";

$res_alunos = Estagiario::query($sqlalunos, array($supervisor_id));
if ($res_alunos === false) die ("Não foi possível consultar a tabela alunos");
$i = 0;
$alunos = array();
foreach ($res_alunos as $item) {
	$alunos[$i]['id_aluno'] = (int)$item->id;
	$alunos[$i]['registro'] = (int)$item->registro;
	$alunos[$i]['nome'] = $item->nome;
	$alunos[$i]['periodo'] = $item->periodo;
	$alunos[$i]['instituicao_id'] = (int)$item->instituicao_id;
	$alunos[$i]['instituicao'] = ($item->instituicao !== null) ? $item->instituicao : 'Sem dados';
	$i++;
}

$id_curso = null;
if (!empty($cress) && $cress != 0 && ctype_digit((string)$cress)) {
	$sqlcurso = "select id from curso_inscricao_supervisor where cress=? limit 1";
	// echo $sqlcurso . "<br />";
	$supervisores_curso = Supervisor::query($sqlcurso, array($cress));
	if (!empty($supervisores_curso)) {
		$id_curso = (int)$supervisores_curso[0]->id;
		// echo "Id curso: " . $id_curso . "<br>";
	}
}

// Instituicoes
$instituicoes = array();
$resultado = Instituicao::query("select id, instituicao from instituicoes order by instituicao");
if ($resultado === false) die ("Não foi possível consultar a tabela instituicoes");
$i = 0;
foreach ($resultado as $item) {
    $instituicoes[$i]['instituicao_id'] = (int)$item->id;
    $instituicoes[$i]['instituicao'] = $item->instituicao;
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