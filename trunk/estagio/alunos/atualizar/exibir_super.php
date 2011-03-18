<?php

include_once("../../setup.php");

$id_instituicao = isset($_REQUEST['id_estagio']) ? $_REQUEST['id_estagio'] : NULL;

$sql = "select supervisores.id, supervisores.nome from supervisores
 inner join inst_super on supervisores.id = inst_super.id_supervisor
 where inst_super.id_instituicao = '$id_instituicao' 
 order by supervisores.nome
";

// echo $sql . "<br>";

$res_supervisor = $db->Execute($sql);
if ($res_supervisor === false) die ("Não foi possível consultar a tabela");

$i = 0;
echo "<option value=0>Seleciona</option>";
while (!$res_supervisor->EOF) {
    $id         = $res_supervisor->fields['id'];
    // $supervisor = utf8_encode($res_supervisor->fields['nome']);
    $supervisor = $res_supervisor->fields['nome'];
    echo "<option value=$id>$supervisor</option>";
    $i++;
    $res_supervisor->MoveNext();
}

$db->close();

exit;

?>