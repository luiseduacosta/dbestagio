<?php

include_once("../../autentica.inc");

$area = $_POST['area'];

$sql = "insert into areas_estagio (area) values ('$area')";

$resultado = $db->Execute($sql);

if ($resultado === false) die ("N�o foi possivel inserir o registro na tabela areas_estagio");

echo "<p>Registro inserido</p>";

exit;

?>