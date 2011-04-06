<?php

// include_once("../autentica.inc");
include_once("../autentica.inc");

// echo "Sistema autentica " . $sistema_autentica;
if($sistema_autentica == 0) {
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=http://$url/estagio/login.php'>";
	exit;
}
// header("Location: http://$url/estagio/login.php");

include_once("../db.inc");
include_once("../setup.php");

$opcao = $_GET['opcao'];

$sql = "select * from estagio order by instituicao";
$resultado = $db->Execute($sql);
if($resultado === false) die ("N�o foi poss�vel consultar a tabela estagio");

$i = 0;
while(!$resultado->EOF) {
    $id_instituicao[$i]   = $resultado->fields['id'];
    $nome_instituicao[$i] = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("opcao",$opcao);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("nome_instituicao",$nome_instituicao);
$smarty->display("instituicao_seleciona.tpl");

exit;

?>