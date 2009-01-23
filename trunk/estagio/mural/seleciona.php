<?php

include_once("mural-autentica.inc");

include_once("../db.inc");
include_once("../setup.php");

$opcao = $_GET['opcao'];

$sql = "select * from mural_estagio where periodo = '" . PERIODO_ATUAL . "' order by instituicao";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela mural_estagio");

$i = 0;
while(!$resultado->EOF)
{
	$id_instituicao[$i]   = $resultado->fields['id'];
    $instituicao[$i] = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("opcao",$opcao);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("instituicao",$instituicao);
$smarty->display("seleciona.tpl");

exit;

?>