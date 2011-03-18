<?php

include_once("../../autentica.inc");

include_once("../../setup.php");

$origem = $_SERVER['HTTP_REFERER'];

$smarty = new Smarty_estagio;

$sql = "select * from alunos order by nome";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Nao foi possivel consultar a tabela alunos");

$i = 0;
while(!$resultado->EOF) {
    $alunos[$i]["id_aluno"] = $resultado->fields["id"];
    $alunos[$i]["registro"] = $resultado->fields["registro"];
    $alunos[$i]["nome"]     = $resultado->fields["nome"];
    $i++;
    $resultado->MoveNext();
}

$smarty->assign("alunos",$alunos);
$smarty->assign("origem",$origem);
$smarty->display("alunos-inserir_seleciona.tpl");

exit;

?>