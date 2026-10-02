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
    	$supervisor_id   = $resultado->fields['id'];
    	$nome_supervisor = $resultado->fields['nome'];
    	$instituicao_id  = isset($resultado->fields['instituicao_id']) ? $resultado->fields['instituicao_id'] : NULL;
    	$email           = $resultado->fields['email'];

    	$supervisores[$i]['supervisor_id']   = $supervisor_id;
    	$supervisores[$i]['nome_supervisor'] = $nome_supervisor;
    	$supervisores[$i]['instituicao_id']  = $instituicao_id;
    	$supervisores[$i]['email']           = $email;

    	$i++;
    	$resultado->MoveNext();
    }
    $smarty = new Smarty_estagio;
    $smarty->assign("supervisores",$supervisores);
    $smarty->display("supervisores_busca_resultado.tpl");
}

exit;

?>