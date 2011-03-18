<?php

include_once("../../autentica.inc");

$id_supervisor  = isset($_REQUEST['id_supervisor']) ? $_REQUEST['id_supervisor'] : NULL;
$id_instituicao = isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL;

require_once("../../db.inc");

$sql_inst_super = "delete from inst_super where id_supervisor=$id_supervisor and id_instituicao=$id_instituicao";
// echo $sql_inst_super . "<br>";
$res_inst_super = $db->Execute($sql_inst_super);
if($res_inst_super === false) die ("Não foi possivel cancelar o registro da tabela inst_super");

// echo "<p>Registro cancelado</p>";

header("Location:../../instituicoes/exibir/ver_cada.php?id_instituicao=$id_instituicao");

exit;

?>
