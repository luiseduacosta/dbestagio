<?php

require_once(__DIR__ . "/../../libphp/models.php");
include_once("../../setup.php");

$palavra = isset($_POST['palavra']) ? $_POST['palavra'] : '';
$palavra_like = "%$palavra%";

$sql = "select * from supervisores where nome like ?";
$resultado = Supervisor::query($sql, array($palavra_like));
if ($resultado === false) die ("Não foi possível consultar a tabela supervisores");
$quantidade = count($resultado);
if ($quantidade === 0) {
    echo "Não há registros com a palavra: $palavra";
    exit;
} else {
    $i = 0;
    $supervisores = array();
    foreach ($resultado as $item) {
    	$supervisor_id   = (int)$item->id;
    	$nome_supervisor = $item->nome;
    	$instituicao_id  = isset($item->instituicao_id) ? (int)$item->instituicao_id : NULL;
    	$email           = $item->email;

    	$supervisores[$i]['supervisor_id']   = $supervisor_id;
    	$supervisores[$i]['nome_supervisor'] = $nome_supervisor;
    	$supervisores[$i]['instituicao_id']  = $instituicao_id;
    	$supervisores[$i]['email']           = $email;

    	$i++;
    }
    $smarty = new Smarty_estagio;
    $smarty->assign("supervisores",$supervisores);
    $smarty->display("supervisores_busca_resultado.tpl");
}

exit;

?>