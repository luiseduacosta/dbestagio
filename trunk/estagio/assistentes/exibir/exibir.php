<?php

// Pego o numero do supervisor
$id_supervisor = $_REQUEST['id_supervisor'];

include_once("../../setup.php");

// Pego as instituicoes na que o supervisor trabalha
$sql_instituicao  = "select e.id, e.instituicao ";
$sql_instituicao .= "from inst_super as i, estagio as e ";
$sql_instituicao .= "where i.id_instituicao=e.id and id_supervisor=$id_supervisor";
// echo $sql_instituicao . "<br />";

$res_instituicao = $db->Execute($sql_instituicao);
if ($res_instituicao === false) die ("Não foi possível consultar as tabelas");

$i = 0;
while (!$res_instituicao->EOF) {
    $instituicao[$i]["id"]          = $res_instituicao->fields['id'];
    $instituicao[$i]["instituicao"] = $res_instituicao->fields['instituicao'];
    $i++;
    $res_instituicao->MoveNext();
}

// Pego os dados do supervisor
$sql  = "select nome, email, cress ";
$sql .= "from supervisores ";
$sql .= "where id=$id_supervisor";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela supervisores");

while (!$resultado->EOF) {
	$nome_supervisor  = $resultado->fields['nome'];
	$email_supervisor = $resultado->fields['email'];
	$cress            = $resultado->fields['cress'];
	$resultado->MoveNext();
}

// Esta consulta eh para construir a caixa de seleçao de instituicoes
$sql_estagio = "select * from estagio order by instituicao";
$res_estagio = $db->Execute($sql_estagio);
if ($res_estagio == false) die ("Não foi possível consultar a tabela estagio");

$i = 0;
while (!$res_estagio->EOF) {
	$num_id_instituicao = $res_estagio->fields['num_id_instituicao'];
	$instituicoes = $res_estagio->fields['instituicao'];
	$matriz_instituicoes[$i]['id'] = $num_id_instituicao;
	$matriz_instituicoes[$i]['instituicoes'] = $instituicoes;
	$i++;
	$res_estagio->MoveNext();
}

// Envio os resultados
include_once("../../setup.php");
$smarty = new Smarty_estagio;

$smarty->assign("id_supervisor",$id_supervisor);
$smarty->assign("cress",$cress);
$smarty->assign("nome_supervisor",$nome_supervisor);
$smarty->assign("email",$email_supervisor);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("matriz_instituicoes",$matriz_instituicoes);
// $smarty->assign("matriz_areas",$matriz_areas);

// Mostro os resultados
$smarty->display("supervisor_exibir.tlp");

$db->close();

exit;

?>