<?php

include_once("../../setup.php");

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

$smarty = new Smarty_estagio;
$smarty->assign("alunos",$alunos);
$smarty->display("alunos-exibir_seleciona.tpl");

exit;

?>
