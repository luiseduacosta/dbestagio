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

// Pego a listagem de todos os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$res_supervisores = $db->Execute($sql_supervisores);
if ($res_supervisores === false) die ("Não foi possível consultar a tabela supervisores");
$i = 0;
while (!$res_supervisores->EOF) {
    $num_supervisor[$i] = $res_supervisores->fields['id'];
    $nome_supervisor[$i] = $res_supervisores->fields['nome'];
    $res_supervisores->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;

$smarty->assign("num_supervisor",$num_supervisor);
$smarty->assign("nome_supervisor",$nome_supervisor);
$smarty->assign("num_instituicao",$num_instituicao);
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("instituicao",$instituicao);
$smarty->display("supervisor_inserir.tpl");

exit;

?>