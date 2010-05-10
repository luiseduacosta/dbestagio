<?php

include_once("../autentica.inc");

include("../db.inc");
include("../setup.php");

$opcao = $_GET['opcao'];

$sql = "select * from supervisores order by nome";
$resultado = $db->Execute($sql);
if($resultado === false) die ("N�o foi poss�vel consultar a tabela supervisores");

$i = 0;
while(!$resultado->EOF) {
    $id_supervisor[$i] = $resultado->fields['id'];
    $nome[$i] = $resultado->fields['nome'];
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;

$smarty->assign("opcao",$opcao);
$smarty->assign("id_supervisor",$id_supervisor);
$smarty->assign("nome",$nome);
$smarty->display("supervisor_seleciona.tlp");

exit;

?>