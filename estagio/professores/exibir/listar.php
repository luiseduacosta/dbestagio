<?php
/*
 * Created on 26/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../../setup.php");

$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : NULL;

$sql  = "select professores.id, professores.nome from estagiarios ";
$sql .= " join professores on estagiarios.id_professor = professores.id ";
if ($periodo) $sql .= " where periodo='$periodo' ";
$sql .= " group by estagiarios.id_professor ";
$sql .= " order by professores.nome ";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if ($resultado == false) die ("Não foi possível consultar a tabela estagiarios");
$i = 0;
while (!$resultado->EOF) {
	$professores[$i]['id_professor'] = $resultado->fields['id'];
	$professores[$i]['nome'] = $resultado->fields['nome'];
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

$smarty = new Smarty_estagio;
$smarty->assign('periodo',$periodo);
$smarty->assign('professores',$professores);
$smarty->display("professores_listar.tpl");

?>
