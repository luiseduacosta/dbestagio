<?php

require_once(__DIR__ . "/../../libphp/models.php");
include_once("../../autentica.inc");

$supervisor_id  = (int)(isset($_REQUEST['supervisor_id']) ? $_REQUEST['supervisor_id'] : 0);
$instituicao_id = (int)(isset($_REQUEST['instituicao_id']) ? $_REQUEST['instituicao_id'] : 0);

if ($supervisor_id > 0 && $instituicao_id > 0) {
    $sql_inst_super = "delete from inst_super where supervisor_id=? and instituicao_id=?";
    // echo $sql_inst_super . "<br>";
    $res_inst_super = Instituicao::query($sql_inst_super, array($supervisor_id, $instituicao_id));
    if ($res_inst_super === false) die ("Não foi possivel cancelar o registro da tabela inst_super");
}

// echo "<p>Registro cancelado</p>";

header("Location:../../instituicoes/ver_cada.php?instituicao_id=$instituicao_id");

exit;

?>