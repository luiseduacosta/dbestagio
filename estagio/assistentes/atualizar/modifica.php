<?php

include_once("../../autentica.inc");
// Pego o numero do supervisor
$id_supervisor = $_REQUEST['id_supervisor'];

include_once("../../db.inc");

// Busco o supervisor
$sql_supervisor = "select cress, nome, email from supervisores where id=$id_supervisor";
// echo $sql_supervisor . "<br>";
$res_supervisor = $db->Execute($sql_supervisor);
if($res_supervisor === false) die ("Não foi possível consultar a tabela supervisores");
while (!$res_supervisor->EOF) {
	$nome  = $res_supervisor->fields['nome'];
	$email = $res_supervisor->fields['email'];
	$cress = $res_supervisor->fields['cress'];
	$res_supervisor->MoveNext();
}

// Busco as instituicoes do supervisor na tabela inst_super
$sql  = "select i.id, i.id_instituicao, e.instituicao "; 
$sql .= "from inst_super as i, estagio as e ";
$sql .= "where i.id_instituicao=e.id and i.id_supervisor=$id_supervisor";
$resultado = $db->Execute($sql);
if($resultado === false) die ("N�o foi poss�vel consultar a tabela inst_super/estagio");

$i = 0;
while (!$resultado->EOF) {
	$instituicao[$i]['id']             = $resultado->fields['id'];
	$instituicao[$i]['id_instituicao'] = $resultado->fields['id_instituicao'];
	$instituicao[$i]['instituicao']    = $resultado->fields['instituicao'];
	$i++;
	$resultado->MoveNext();
}

// Pego as instituicoes para a caixa de selecao
$sql_estagio = "select * from estagio order by instituicao";
$res_estagio = $db->Execute($sql_estagio);
if($res_estagio === false) die ("N�o foi poss�vel consultar a tabela estagio");

$i = 0;
while(!$res_estagio->EOF) {
	$id_instituicao = $res_estagio->fields['id'];
	$instituicoes   = $res_estagio->fields['instituicao'];
	$matriz_instituicoes[$i]['id'] = $id_instituicao;
	$matriz_instituicoes[$i]['instituicoes'] = $instituicoes;
	$i++;
	$res_estagio->MoveNext();
}

// Envio os resultados
include_once("../../setup.php");
$smarty = new Smarty_estagio;

$smarty->assign("id_supervisor",$id_supervisor);
$smarty->assign("nome",$nome);
$smarty->assign("email",$email);
$smarty->assign("cress",$cress);
$smarty->assign("instituicao",$instituicao); // instituicoes do supervisor
$smarty->assign("matriz_instituicoes",$matriz_instituicoes);
// $smarty->assign("matriz_areas",$matriz_areas);

// Mostro os resultados
$smarty->display("supervisor_modifica.tlp");

$db->close();

exit;

?>