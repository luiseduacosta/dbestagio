<?php

include_once("../../autentica.inc");

$id_area = $_REQUEST["id_area"];

include_once("../../db.inc");
$sql_estagio = "select area from estagio where area=$id_area";
$res_estagio = $db->Execute($sql_estagio);
if($res_estagio === false) die ("N�o foi poss�vel consultar a tabela estagio");
$quantidade = $res_estagio->RecordCount();
if($quantidade > 0)
{
    echo "<p>Opera��o abortada porque $quantidade institui��es dependem desta �rea</p>";
    exit;
}
else
{
    $sql = "delete from areas_estagio where id=$id_area";
    $resultado = $db->Execute($sql);
    echo "Registro cancelado" . "<br>";
}

exit;

?>