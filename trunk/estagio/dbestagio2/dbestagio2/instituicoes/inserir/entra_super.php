<?php

include_once("../../autentica.inc");

$cress = $_POST['cress'];
$nome  = $_POST['nome'];
$email = $_POST['email'];
$id_instituicao = $_POST['id_instituicao'];
$id_supervisor  = $_POST['id_supervisor'];

include_once("../../db.inc");

if($id_supervisor == 0)
{
    $sql = "insert into supervisores (cress,nome,email) values('$cress','$nome','$email')";
    $resultado = $db->Execute($sql);
    if($resultado === false) die ("Não foi possível inserir dados na tabela supervisores");

    // Obtenho o numero do ultimo supervisor ingressado
    $res_ultimo = $db->Execute("select max(id) as ultimo_supervisor from supervisores");
    if($res_ultimo === false) die ("Não foi possível consultar a tabela supervisores");
    $id_supervisor = $res_ultimo->fields['ultimo_supervisor'];
}

// Insero supervisor e instituicao em inst_super
$sql_inst_super = "insert into inst_super (id_supervisor, id_instituicao) values ('$id_supervisor', '$id_instituicao')";
$res_inst_super = $db->Execute($sql_inst_super);
if($res_inst_super === false) die ("Não foi possível inserir o registro na tabela inst_super");

header("Location:form_supervisor.php?id_instituicao=$id_instituicao");
// require("form_supervisor.php");

?>