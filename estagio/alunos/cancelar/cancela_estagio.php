<?php

include_once("../../setup.php");

$id_estagiarios = isset($_GET['id_estagiarios']) ? $_GET['id_estagiarios'] : NULL;
$id_aluno = isset($_GET['id_aluno']) ? $_GET['id_aluno'] : NULL;

$sql = "delete from estagiarios where id='$id_estagiarios'";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível cancelar o registro da tabela estagiarios");

header("Location:../inserir/acrescentar_estagio.php?id_aluno=$id_aluno");

?>