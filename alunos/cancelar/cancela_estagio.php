<?php

include_once("../../setup.php");

$estagiario_id = isset($_GET['estagiario_id']) ? $_GET['estagiario_id'] : NULL;
$aluno_id      = isset($_GET['aluno_id']) ? $_GET['aluno_id'] : NULL;

$sql = "delete from estagiarios where id='$estagiario_id'";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível cancelar o registro da tabela estagiarios");

header("Location:../inserir/acrescentar_estagio.php?aluno_id=$aluno_id");

?>