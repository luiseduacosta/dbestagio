<?php

// Pego o numero do supervisor
$supervisor_id = isset($_REQUEST['supervisor_id']) ? $_REQUEST['supervisor_id'] : (isset($_REQUEST['id_supervisor']) ? $_REQUEST['id_supervisor'] : NULL);

include_once("../../autentica.inc");

// Pego as instituicoes na que o supervisor trabalha
$sql_instituicao  = "select e.id, e.instituicao ";
$sql_instituicao .= "from inst_super as i, instituicoes as e ";
$sql_instituicao .= "where i.instituicao_id=e.id and supervisor_id=$supervisor_id";
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
$sql .= "where id=$supervisor_id";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela supervisores");

while (!$resultado->EOF) {
	$nome_supervisor  = $resultado->fields['nome'];
	$email_supervisor = $resultado->fields['email'];
	$cress            = $resultado->fields['cress'];
	$resultado->MoveNext();
}

// Esta consulta eh para construir a caixa de seleçao de instituicoes
$sql_estagio = "select * from instituicoes order by instituicao";
$res_estagio = $db->Execute($sql_estagio);
if ($res_estagio == false) die ("Não foi possível consultar a tabela instituicoes");

$i = 0;
while (!$res_estagio->EOF) {
	$instituicao_id = $res_estagio->fields['id'];
	$instituicoes = $res_estagio->fields['instituicao'];
	$matriz_instituicoes[$i]['instituicao_id'] = $instituicao_id;
	$matriz_instituicoes[$i]['instituicoes'] = $instituicoes;
	$i++;
	$res_estagio->MoveNext();
}

// Envio os resultados
$smarty = new Smarty_estagio;
$smarty->assign("supervisor_id",$supervisor_id);
$smarty->assign("cress",$cress);
$smarty->assign("nome_supervisor",$nome_supervisor);
$smarty->assign("email",$email_supervisor);
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("matriz_instituicoes",$matriz_instituicoes);
// $smarty->assign("matriz_areas",$matriz_areas);

// Mostro os resultados
$smarty->display("supervisor_exibir.tpl");

exit;

?>