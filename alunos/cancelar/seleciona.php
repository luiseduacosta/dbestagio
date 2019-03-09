<?php

include_once("../../setup.php");
include_once("../../autentica.inc");

$smarty = new Smarty_estagio;

$sql = "select * from alunos order by nome";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela alunos");

$i = 0;
while (!$resultado->EOF) {
    $alunos[$i]["id_aluno"] = $resultado->fields["id"];
    $alunos[$i]["registro"] = $resultado->fields["registro"];
    $alunos[$i]["nome"]     = $resultado->fields["nome"];
    $i++;
    $resultado->MoveNext();
}

$smarty->assign("alunos",$alunos);
$smarty->display("alunos-cancelar_seleciona.tpl");

exit;

?>