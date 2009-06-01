<?php

include("../../db.inc");
include("../../setup.php");

$sql = "select * from estagio order by instituicao";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela estagio");

$i = 0;
while(!$resultado->EOF)
{
    $id_instituicao[$i] = $resultado->fields['id'];
    $instituicoes[$i]    = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;

$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("instituicoes",$instituicoes);
$smarty->display("instituicao_exibir_seleciona.tlp");

exit;

?>