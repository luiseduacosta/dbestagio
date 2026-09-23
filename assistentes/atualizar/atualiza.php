<?php

require_once("../../autentica.inc");

$instituicao_id   = isset($_POST['instituicao_id']) ? $_POST['instituicao_id'] : NULL;
$supervisor_id    = isset($_POST['supervisor_id']) ? $_POST['supervisor_id']: NULL;

$nome  = $_POST['nome'];
$email = $_POST['email'];
$cress = $_POST['cress'];

$sql = "update supervisores set nome='$nome', email='$email', cress='$cress' where id=$supervisor_id";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível atualizar a tabela supervisores");

/*
if(!empty($id_instituicao)) {
    $sql_inst_super = "update inst_super set instituicao_id = '$id_instituicao' where supervisor_id = $id_supervisor";
    echo $sql_inst_super . "<br>";
}
*/

header("Location:../exibir/ver_cada.php?supervisor_id=$supervisor_id");

exit;

?>