<?php

include_once("../../autentica.inc");

$id_area = $_POST["id_area"];
$area    = $_POST["area"];

include_once("../../db.inc");

$sql = "update areas_estagio set area='$area' where id=$id_area";
$resultado = $db->Execute($sql);

if($resultado === false) die ("Não foi possível atualizar a tabela areas_estagio"); 

header("Location:../exibir/listar.php");
// echo "<p>Registro atualizadao</p>";

exit;

?>