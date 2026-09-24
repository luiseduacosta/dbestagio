<?php

// include_once("../autentica.inc");
include_once("../autentica.inc");

// echo "Sistema autentica " . $sistema_autentica;
if ($sistema_autentica == 0) {
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=http://$url/estagio/login.php'>";
	exit;
}
// header("Location: http://$url/estagio/login.php");

$opcao = $_GET['opcao'];

$sql = "select * from instituicoes order by instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela instituicoes");

$i = 0;
while (!$resultado->EOF) {
    $instituicao_id[$i]   = $resultado->fields['id'];
    $nome_instituicao[$i] = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("opcao",$opcao);
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("nome_instituicao",$nome_instituicao);
$smarty->display("instituicao_seleciona.tpl");

exit;

?>