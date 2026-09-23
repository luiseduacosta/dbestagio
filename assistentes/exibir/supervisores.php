<?php

include_once("../../setup.php");

$instituicao_id = $_GET['instituicao_id'];
$ordem = $_GET['ordem'];
if (empty($ordem)) $ordem="supervisor";

$sql = "select s.id as supervisor_id, s.cress, s.nome, s.email "
. " from supervisores s, inst_super j "
. " where s.id = j.supervisor_id and "
. " j.instituicao_id = $instituicao_id "
. " order by s.nome ";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela supervisores e inst_super");
$i = 0;
while (!$resultado->EOF) {
    $supervisores[$i]['cress']         = $resultado->fields['cress'];
    $supervisores[$i]['nome']          = $resultado->fields['nome'];
    $supervisores[$i]['email']         = $resultado->fields['email'];
    $supervisores[$i]['supervisor_id'] = $resultado->fields['supervisor_id'];
    // echo $resultado->fields['id_supervisor'] . " " . $supervisores[$i]['id_supervisor'] . "<br>";
    $i++;
    $resultado->MoveNext();
}

// Busco o nome da instituicao
$sql_instituicao = "select instituicao from instituicoes where id=$instituicao_id";
$res_instituicao = $db->Execute($sql_instituicao);
if ($res_instituicao === false) die ("Nao foi possivel consutar a tabela instituicoes");
$instituicao = $res_instituicao->fields['instituicao'];

$smarty = new Smarty_estagio;
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("supervisores",$supervisores);
$smarty->display("supervisores_x_instituicao.tpl");

$db->Close();

exit;

?>