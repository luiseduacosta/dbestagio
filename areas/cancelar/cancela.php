<?php

include_once("../../autentica.inc");

$id_area = $_REQUEST["id_area"];
$sql_estagio = "select area from estagio where area=$id_area";
$res_estagio = $db->Execute($sql_estagio);
if ($res_estagio === false) die ("Nao foi possvel consultar a tabela estagio");
$quantidade = $res_estagio->RecordCount();
if($quantidade > 0) {
    echo "<p>Opera��o abortada porque $quantidade institui��es dependem desta �rea</p>";
    exit;
} else {
    $sql = "delete from areas_estagio where id=$id_area";
    $resultado = $db->Execute($sql);
    echo "Registro exclu�do" . "<br>";
}

exit;

?>