<?php

include_once("../../autentica.inc");

$id_instituicao   = isset($_POST['id_instituicao']) ? $_POST['id_instituicao'] : NULL;
$id_supervisor    = isset($_POST['id_supervisor']) ? $_POST['id_supervisor']: NULL;

$nome  = $_POST['nome'];
$email = $_POST['email'];
$cress = $_POST['cress'];

include_once("../../setup.php");

$sql = "update supervisores set nome='$nome', email='$email', cress='$cress' where id=$id_supervisor";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);

if ($resultado === false) die ("Não foi possível atualizar a tabela supervisores");

/*
if(!empty($id_instituicao)) {
    $sql_inst_super = "update inst_super set id_instituicao = '$id_instituicao' where id_supervisor = $id_supervisor";
    echo $sql_inst_super . "<br>";
}
*/

header("Location:../exibir/ver_cada.php?id_supervisor=$id_supervisor");

exit;

?>