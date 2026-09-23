<?php

include_once("../../autentica.inc");

$cress = $_POST['cress'];
$nome  = $_POST['nome'];
$email = $_POST['email'];
$instituicao_id = $_POST['instituicao_id'] ? $_POST['instituicao_id'] : NULL;
$supervisor_id  = $_POST['supervisor_id'] ? $_POST['supervisor_id'] : NULL;

if($supervisor_id == 0) {
    $sql = "insert into supervisores (cress,nome,email) values('$cress','$nome','$email')";
    $resultado = $db->Execute($sql);
    if ($resultado === false) die ("Não foi possível inserir dados na tabela supervisores");

    // Obtenho o numero do ultimo supervisor ingressado
    $res_ultimo = $db->Execute("select max(id) as ultimo_supervisor from supervisores");
    if ($res_ultimo === false) die ("Não foi possível consultar a tabela supervisores");
    $supervisor_id = $res_ultimo->fields['ultimo_supervisor'];
}

// Insero supervisor e instituicao em inst_super
$sql_inst_super = "insert into inst_super (supervisor_id, instituicao_id) values ('$supervisor_id', '$instituicao_id')";
$res_inst_super = $db->Execute($sql_inst_super);
if ($res_inst_super === false) die ("Não foi possível inserir o registro na tabela inst_super");

header("Location:../exibir/ver_cada.php?instituicao_id=$instituicao_id");
// require("form_supervisor.php");

?>