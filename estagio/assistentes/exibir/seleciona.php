<?php

include("../../db.inc");
include("../../setup.php");

$sql = "select * from supervisores order by nome";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela novo_supervisores");

$i = 0;
while(!$resultado->EOF)
{
    $id_supervisor[$i] = $resultado->fields['id'];
    $nome[$i] = $resultado->fields['nome'];
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;

$smarty->assign("id_supervisor",$id_supervisor);
$smarty->assign("nome",$nome);
$smarty->display("supervisor_exibir_seleciona.tlp");

exit;

?>