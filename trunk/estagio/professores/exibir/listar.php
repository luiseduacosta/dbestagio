<?php
/*
 * Created on 26/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../../setup.php");

$sql_periodo = "select max(periodo) as periodo from estagiarios";
$res_periodo = $db->Execute($sql_periodo);
$periodo_atual = $res_periodo->fields['periodo'];

$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : $periodo_atual;

$sql  = "select areas_estagio.id as areas_id, areas_estagio.area, professores.id as professores_id, professores.nome, professores.departamento, estagiarios.periodo, estagiarios.turno ";
$sql .= " from areas_estagio ";
$sql .= " join estagiarios on areas_estagio.id = estagiarios.id_area  ";
$sql .= " join professores on estagiarios.id_professor = professores.id  ";
if ($periodo) $sql .= " where estagiarios.periodo = '$periodo' ";
$sql .= " group by estagiarios.id_area, professores.id ";
$sql .= " order by professores.nome ";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado == false) die ("Não foi possível consultar a tabela estagiarios");
$i = 0;
while (!$resultado->EOF) {
	$professores[$i]['id_professor'] = $resultado->fields['professores_id'];
	$professores[$i]['nome'] = $resultado->fields['nome'];
	$professores[$i]['departamento'] = $resultado->fields['departamento'];
	$professores[$i]['id_area'] = $resultado->fields['areas_id'];
	$professores[$i]['area'] = $resultado->fields['area'];
	$professores[$i]['turno'] = $resultado->fields['turno'];
		
	// Calculo a quantidade de alunos por professor	
	$id_professor = $resultado->fields['professores_id'];
	$id_area = $resultado->fields['areas_id'];

	// $sql_alunos = "select id_aluno as q_alunos from estagiarios where id_professor = $id_professor and id_area= $id_area";
	$sql_alunos = "select id_aluno as q_alunos from estagiarios where id_area= '$id_area' and id_professor = '$id_professor' ";
	if ($periodo) $sql_alunos .= " and periodo='$periodo' ";
	$sql_alunos .= " group by id_aluno ";
	// echo $sql_alunos . "<br>";
	$res_alunos = $db->Execute($sql_alunos);
	if ($res_alunos == false) die ("Não foi possível consultar a tabela estagiarios");	
	$q_alunos = $res_alunos->RecordCount();

	$professores[$i]['q_alunos'] = $q_alunos;
	
	$total_alunos = $total_alunos + $q_alunos;
	// echo $total_alunos . "<br>";

	$i++;
	$resultado->MoveNext();
}

/* Debugg 
$matriz = $professores;

for($i=0;$i<sizeof($matriz);$i++) {

	print $i . " "; 
	print $matriz[$i]['id_professor'] . " ";      
	print $matriz[$i]['nome'] . "<br>";
	// print $matriz[$i]['instituicao'] . "<br>";

}
*/

// Pego a informacao sobre as turma de alunos
$sqlturma = "select id, periodo from estagiarios group by periodo";
// echo $sqlturma . "<br>";
$res_turma = $db->Execute($sqlturma);
if ($res_turma === false) die ("Não foi possivel consultar a tabela estagiarios");
while (!$res_turma->EOF) {
	$periodos[] = $res_turma->fields['periodo'];
	$res_turma->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("periodo",$periodo);
$smarty->assign("periodos",$periodos);
$smarty->assign("total_alunos",$total_alunos);
$smarty->assign("professores",$professores);
$smarty->display("professores_listar.tpl");

?>
