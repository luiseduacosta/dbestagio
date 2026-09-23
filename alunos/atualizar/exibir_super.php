<?php

include_once("../../setup.php");

$instituicao_id = isset($_REQUEST['instituicao_id']) ? $_REQUEST['instituicao_id'] : NULL;

$sql = "select supervisores.id, supervisores.nome from supervisores
 inner join inst_super on supervisores.id = inst_super.supervisor_id
 where inst_super.instituicao_id = '$instituicao_id' 
 order by supervisores.nome
";

// echo $sql . "<br>";

$res_supervisor = $db->Execute($sql);
if ($res_supervisor === false) die ("Não foi possível consultar a tabela");

$i = 0;
echo "<option value=0>Seleciona</option>";
while (!$res_supervisor->EOF) {
    $supervisor_id = $res_supervisor->fields['id'];
    // $supervisor = utf8_encode($res_supervisor->fields['nome']);
    $supervisor = $res_supervisor->fields['nome'];
    echo "<option value=$supervisor_id>$supervisor</option>";
    $i++;
    $res_supervisor->MoveNext();
}

$db->close();

exit;

?>