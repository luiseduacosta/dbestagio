<?php

$palavra = $_POST['instituicao'];
$palavra = strtoupper($palavra);

include_once("../../db.inc");
include_once("../../setup.php");

$sql = "select * from estagio where instituicao like '%$palavra%' order by instituicao";
$resultado = $db->Execute($sql);
if($resultado === false) die ("N�o foi poss�vel consultar a tabela estagio");
$quantidade = $resultado->RecordCount();
if($quantidade === 0) {
    echo "N�o h� registros com a palavra: $palavra";
    exit;
} else {
    $i = 0;
    while (!$resultado->EOF) {
		$instituicao[$i]['id'] = $resultado->fields['id'];
		$instituicao[$i]['instituicao'] = $resultado->fields['instituicao'];
		$i++;
		$resultado->MoveNext();
    }
    $smarty = new Smarty_estagio;
    $smarty->assign("instituicao",$instituicao);
    $smarty->display("instituicao_busca_resultado.tpl");
}

?>