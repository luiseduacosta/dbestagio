<?php

require_once("../../autentica.inc");

require_once("../../setup.php");
require_once("../../db.inc");

$id_instituicao = isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL;

$sql = "select * from estagio order by instituicao";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela estagio");

$i = 0;
while(!$resultado->EOF) {
    $num_instituicao[$i] = $resultado->fields['id'];
    $instituicao[$i]     = $resultado->fields['instituicao'];
    $i++;
    $resultado->MoveNext();
}

// Pego a listagem de todos os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$res_supervisores = $db->Execute($sql_supervisores);
if($res_supervisores === false) die ("Não foi possível consultar a tabela supervisores");
$i = 0;
while(!$res_supervisores->EOF) {
    $num_supervisor[$i] = $res_supervisores->fields['id'];
    $nome_supervisor[$i] = $res_supervisores->fields['nome'];
    $res_supervisores->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;

$smarty->assign("num_supervisor",$num_supervisor);
$smarty->assign("nome_supervisor",$nome_supervisor);
$smarty->assign("num_instituicao",$num_instituicao);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("instituicao",$instituicao);
$smarty->display("supervisor_inserir.tlp");

exit;

?>