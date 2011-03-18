<?php

include_once("../../autentica.inc");

$id_area = $_REQUEST["id_area"];

include_once("../../setup.php");
$sql_estagio = "select area from estagio where area=$id_area";
$res_estagio = $db->Execute($sql_estagio);
if($res_estagio === false) die ("Nao foi possvel consultar a tabela estagio");
$quantidade = $res_estagio->RecordCount();
if($quantidade > 0)
{
    echo "<p>Operação abortada porque $quantidade instituições dependem desta área</p>";
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