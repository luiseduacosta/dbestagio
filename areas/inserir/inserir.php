<?php

include_once("../../autentica.inc");

$area = $_POST['area'];

$sql = "insert into areas (area) values ('$area')";

$resultado = $db->Execute($sql);

if ($resultado === false) die ("N�o foi possivel inserir o registro na tabela areas");

echo "<p>Registro inserido</p>";

exit;

?>