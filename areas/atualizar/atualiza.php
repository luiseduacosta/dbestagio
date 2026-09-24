<?php

include_once("../../autentica.inc");

$id_area = $_POST["id_area"];
$area    = $_POST["area"];

$sql = "update areas set area='$area' where id=$id_area";
$resultado = $db->Execute($sql);

if ($resultado === false) die ("Não foi possivel atualizar a tabela areas"); 

header("Location:../exibir/listar.php");
// echo "<p>Registro atualizadao</p>";

exit;

?>