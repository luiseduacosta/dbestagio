<?php

include_once("../../setup.php");

$id_area = $_GET['id_area'];
$ordem = $_GET['ordem'];

if (empty($ordem)) $ordem="instituicao";

// Pego o nome da área
$sql_areas_estagio = "select area from areas_estagio where id=$id_area";
$res_areas_estagio = $db->Execute($sql_areas_estagio);
if ($res_areas_estagio === false) die ("Nao foi possivel consultar a tabela areas_estagio");
while(!$res_areas_estagio->EOF) {
    $nome_area = $res_areas_estagio->fields['area'];
    $res_areas_estagio->MoveNext();
}

$sql = "select e.id as num_instituicao, e.instituicao, e.beneficio as bolsa, max(t.periodo) as turma, e.endereco, e.telefone, e.area "
	. " from estagio e "
	. " left outer join estagiarios t on e.id = t.id_instituicao "
	. " where e.area = '$id_area' "
	. " group by e.id, e.instituicao, e.area, e.beneficio, e.endereco, e.telefone "
	. " order by $ordem";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar as tabelas estagio, estagiarios");

$i = 0;
while (!$resultado->EOF) {
    $matriz[$i]["id"]          = $resultado->fields['num_instituicao'];
    $matriz[$i]["instituicao"] = $resultado->fields['instituicao'];
    $matriz[$i]["endereco"]    = $resultado->fields['endereco'];
    $matriz[$i]["telefone"]    = $resultado->fields['telefone'];

    $num_instituicao = $resultado->fields['num_instituicao'];
	$sql_periodo     = "select max(periodo) as turma from estagiarios where id_instituicao=$num_instituicao";
	$resultado_periodo = $db->Execute($sql_periodo);
	if ($resultado_periodo === false) die ("Nao foi possivel consultar a tabela estagiarios");
	while (!$resultado_periodo->EOF) {
	    $matriz[$i]["turma"] = $resultado_periodo->fields['turma'];
		$resultado_periodo->MoveNext();
	}

	// q_super_por_instituicao
	$sql_super_por_instituicao = "select count(*) as q_super from inst_super where id_instituicao=$num_instituicao";
	$resultado_super = $db->Execute($sql_super_por_instituicao);
	if ($resultado_super === false) die ("Nao foi possivel consultar a tabela inst_super");
	// print_r($resultado_super_por_instituicao);
	while (!$resultado_super->EOF) {
		$matriz[$i]["q_supervisores"] = $resultado_super->fields['q_super'];
		$quantidade_super = $resultado_super->fields['q_super'];
		$resultado_super->MoveNext();
	}
	$i++;

	$resultado->MoveNext();
}

/*
include_once("../../../adodb/adodb-pager.inc.php");
$pager = new ADODB_Pager($db,$sql);
$pager->Render();
*/

$smarty = new Smarty_estagio;
$smarty->assign("id_area",$id_area);
$smarty->assign("nome_area",$nome_area);
$smarty->assign("instituicoes",$matriz);
$smarty->display("area_instituicoes.tlp");

exit;

?>