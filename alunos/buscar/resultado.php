<?php

$palavra = $_POST['palavra'];

include_once("../../setup.php");

$sql = "select * from alunos where nome like '%$palavra%' order by nome";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela alunos");
$quantidade = $resultado->RecordCount();
if ($quantidade === 0) {
    echo "Não há registros com a palavra: $palavra";
    exit;
} else {
    $i = 0;
    while (!$resultado->EOF) {
	$alunos[$i]['id_aluno'] = $resultado->fields['id'];
	$alunos[$i]['nome']     = $resultado->fields['nome'];
	$alunos[$i]['registro'] = $resultado->fields['registro'];
	$alunos[$i]['email']    = $resultado->fields['email'];

	$i++;
	$resultado->MoveNext();
    }
    $smarty = new Smarty_estagio;
    $smarty->assign("alunos",$alunos);
    $smarty->display("alunos-busca_resultado.tpl");
}

exit;

?>