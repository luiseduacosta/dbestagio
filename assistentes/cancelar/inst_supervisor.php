<?php

include_once("../../autentica.inc");

$supervisor_id  = isset($_REQUEST['supervisor_id']) ? $_REQUEST['supervisor_id'] : NULL;
$instituicao_id = isset($_REQUEST['instituicao_id']) ? $_REQUEST['instituicao_id'] : NULL;

$sql_inst_super = "delete from inst_super where supervisor_id=$supervisor_id and instituicao_id=$instituicao_id";
// echo $sql_inst_super . "<br>";
$res_inst_super = $db->Execute($sql_inst_super);
if ($res_inst_super === false) die ("Não foi possivel cancelar o registro da tabela inst_super");

// echo "<p>Registro cancelado</p>";

header("Location:../../instituicoes/exibir/ver_cada.php?instituicao_id=$instituicao_id");

exit;

?>
