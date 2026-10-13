<?php

require_once(__DIR__ . "/../../libphp/models.php");
require_once("../../autentica.inc");

$instituicao_id = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : (isset($_REQUEST['id_instituicao']) ? (int)$_REQUEST['id_instituicao'] : 0);

$sql = "select * from instituicoes order by instituicao";
$resultado = Instituicao::query($sql);
if($resultado === false) die ("Não foi possível consultar a tabela instituicoes");

$i = 0;
$num_instituicao = array();
$instituicao = array();
foreach ($resultado as $item) {
    $num_instituicao[$i] = (int)$item->id;
    $instituicao[$i]     = $item->instituicao;
    $i++;
}

$smarty = new Smarty_estagio;

$smarty->assign("num_instituicao",$num_instituicao);
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("instituicao",$instituicao);
$smarty->display("supervisor_inserir.tpl");

exit;

?>