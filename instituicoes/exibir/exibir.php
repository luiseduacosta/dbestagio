<?php

include_once("../../db.inc");

// Pego o numero do instituicao
$instituicao_id = isset($_REQUEST['instituicao_id']) ? $_REQUEST['instituicao_id'] : (isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL);

$sql_estagio = "select e.id, e.instituicao, e.endereco, e.cep, e.telefone, e.beneficios, e.fim_de_semana, a.area from instituicoes as e, areas as a where e.area=a.id and e.id=$instituicao_id";
$res_estagio = $db->Execute($sql_estagio);
// echo $sql_estagio . "<br>";
if ($res_estagio === false) die ("Não foi possível consultar a tabela instituicoes");

while (!$res_estagio->EOF) {
	$instituicao_id    = $res_estagio->fields['id'];
	$instituicao       = $res_estagio->fields['instituicao'];
	$endereco          = $res_estagio->fields['endereco'];
	$cep               = $res_estagio->fields['cep'];
	$telefone          = $res_estagio->fields['telefone'];
	$beneficio	       = $res_estagio->fields['beneficios'];
	$fim_de_semana     = $res_estagio->fields['fim_de_semana'];

	// Busco os supervisores por instituicao
	$sql  = "select s.id, s.cress, s.nome from supervisores as s, inst_super as j ";
	$sql .= "where j.supervisor_id=s.id and j.instituicao_id=$instituicao_id ";
	$sql .= "order by nome";
	$resultado = $db->Execute($sql);
	if ($resultado === false) die ("Não foi possível consultar a tabela supervisores");
	$i = 0;
	while (!$resultado->EOF) {
	    $supervisores[$i]['id']    = $resultado->fields['id'];
	    $supervisores[$i]['cress'] = $resultado->fields['cress'];
	    $supervisores[$i]['nome']  = $resultado->fields['nome'];
	    $i++;
	    $resultado->MoveNext();
	}

	// Pego a turma das instituicoes
/*
	$sql_turma = "select max(turma) as turma from turma_estagio where instituicao=$id_instituicao";
	echo $sql_turma . "<br>";
	$resultado = $db->Execute($sql_turma);
	if ($resultado === false) die ("Não foi possível consultar a tabela turma_estagio");
	while (!$resultado->EOF) {
	    $turma = $resultado->fields['turma'];
	    $resultado->MoveNext();
	}
*/
	$res_estagio->MoveNext();
}

// Envio os resultados
include_once("../../setup.php");
$smarty = new Smarty_estagio;

$smarty->assign("id",$instituicao_id);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("endereco",$endereco);
$smarty->assign("cep",$cep);
$smarty->assign("telefone",$telefone);
$smarty->assign("beneficio",$beneficio);
$smarty->assign("fim_de_semana",$fim_de_semana);
$smarty->assign("supervisores",$supervisores);
$smarty->display("instituicao_exibir.tlp");

exit;

?>
