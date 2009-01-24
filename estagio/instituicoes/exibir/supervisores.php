<?php

$id_instituicao = $_GET['id_instituicao'];
$ordem = $_GET['ordem'];
if(empty($ordem))
    $ordem="supervisor";

include_once("../../db.inc");
include_once("../../setup.php");

$sql = "select s.id, s.cress, s.nome, s.email "
. " from supervisores s, inst_super j "
. " where s.id = j.id_supervisor and "
. " j.id_instituicao = $id_instituicao "
. " order by s.nome ";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela supervisores e inst_super");
$i = 0;
while(!$resultado->EOF) {
    $cress[$i]         = $resultado->fields['cress'];
    $nome[$i]          = $resultado->fields['nome'];
    $email[$i]         = $resultado->fields['email'];
    $id_supervisor[$i] = $resultado->fields['id'];
    $i++;
    $resultado->MoveNext();
}

// Busco o nome da instituicao
$sql_instituicao = "select instituicao from estagio where id=$id_instituicao";
$resultado_instituicao = $db->Execute($sql_instituicao);
if($resultado_instituicao === false) die ("Não foi possivel consutar a tabela estagio");
$instituicao = $resultado_instituicao->fields['instituicao'];

$smarty = new Smarty_estagio;
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("id_supervisor",$id_supervisor);
$smarty->assign("cress",$cress);
$smarty->assign("nome_supervisor",$nome);
$smarty->assign("email_supervisor",$email);
$smarty->display("supervisores_x_instituicao.tpl");

?>