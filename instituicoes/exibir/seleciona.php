<?php

include("../../setup.php");

$sql = "select * from instituicoes order by instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela instituicoes");

$i = 0;
while(!$resultado->EOF) {
    $instituicao_id[$i] = $resultado->fields['id'];
    $instituicoes[$i]   = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;

$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("instituicoes",$instituicoes);
$smarty->display("instituicao_exibir_seleciona.tpl");

exit;

?>