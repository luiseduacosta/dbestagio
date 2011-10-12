<?php

include_once("../../setup.php");

$id_instituicao = $_GET['id_instituicao'];
$ordem = $_GET['ordem'];
if (empty($ordem)) $ordem="supervisor";

$sql = "select s.id as id_supervisor, s.cress, s.nome, s.email "
. " from supervisores s, inst_super j "
. " where s.id = j.id_supervisor and "
. " j.id_instituicao = $id_instituicao "
. " order by s.nome ";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela supervisores e inst_super");
$i = 0;
while (!$resultado->EOF) {
    $supervisores[$i]['cress']         = $resultado->fields['cress'];
    $supervisores[$i]['nome']          = $resultado->fields['nome'];
    $supervisores[$i]['email']         = $resultado->fields['email'];
    $supervisores[$i]['id_supervisor'] = $resultado->fields['id_supervisor'];
    // echo $resultado->fields['id_supervisor'] . " " . $supervisores[$i]['id_supervisor'] . "<br>";
    $i++;
    $resultado->MoveNext();
}

// Busco o nome da instituicao
$sql_instituicao = "select instituicao from estagio where id=$id_instituicao";
$res_instituicao = $db->Execute($sql_instituicao);
if ($res_instituicao === false) die ("Nao foi possivel consutar a tabela estagio");
$instituicao = $res_instituicao->fields['instituicao'];

$smarty = new Smarty_estagio;
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("supervisores",$supervisores);
$smarty->display("supervisores_x_instituicao.tpl");

$db->Close();

exit;

?>