<?php

include("../../setup.php");

$sql = "select * from supervisores order by nome";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela novo_supervisores");

$i = 0;
while (!$resultado->EOF) {
    $supervisor_id[$i] = $resultado->fields['id'];
    $nome[$i] = $resultado->fields['nome'];
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("supervisor_id",$supervisor_id);
$smarty->assign("nome",$nome);
$smarty->display("supervisor_exibir_seleciona.tpl");

$db->Close();

exit;

?>