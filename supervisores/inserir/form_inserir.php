<?php

require_once("../../autentica.inc");

$instituicao_id = isset($_REQUEST['instituicao_id']) ? $_REQUEST['instituicao_id'] : (isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL);

$sql = "select * from instituicoes order by instituicao";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela instituicoes");

$i = 0;
while (!$resultado->EOF) {
    $num_instituicao[$i] = $resultado->fields['id'];
    $instituicao[$i]     = $resultado->fields['instituicao'];
    $i++;
    $resultado->MoveNext();
}

$smarty = new Smarty_estagio;

$smarty->assign("num_instituicao",$num_instituicao);
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("instituicao",$instituicao);
$smarty->display("supervisor_inserir.tpl");

exit;

?>