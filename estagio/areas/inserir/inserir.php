<?php

include_once("../../autentica.inc");

$area = $_POST['area'];

include_once("../../db.inc");

$sql = "insert into areas_estagio (area) values ('$area')";

$resultado = $db->Execute($sql);

if($resultado === false) die ("Nao foi possivel inserir o registro na tabela areas_estagio");

echo "<p>Registro inserido</p>";

exit;

?>