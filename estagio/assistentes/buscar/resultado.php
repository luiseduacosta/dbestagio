<?php

include_once("../../setup.php");

$palavra = $_POST['palavra'];

$sql = "select * from supervisores where nome like '%$palavra%'";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela supervisores");
$quantidade = $resultado->RecordCount();
if ($quantidade === 0) {
    echo "Não há registros com a palavra: $palavra";
    exit;
} else {
    $i = 0;
    while (!$resultado->EOF) {
    	$id_supervisor   = $resultado->fields['id'];
    	$nome_supervisor = $resultado->fields['nome'];
    	$id_instituicao  = $resultado->fields['id_instituicao'];
    	$email           = $resultado->fields['email'];

    	$supervisores[$i]['id_supervisor']   = $id_supervisor;
    	$supervisores[$i]['nome_supervisor'] = $nome_supervisor;
    	$supervisores[$i]['id_instituicao']  = $id_instituicao;
    	$supervisores[$i]['email']           = $email;

    	$i++;
    	$resultado->MoveNext();
    }
    $smarty = new Smarty_estagio;
    $smarty->assign("supervisores",$supervisores);
    $smarty->display("supervisores_busca_resultado.tlp");
}

exit;

?>