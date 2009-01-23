<?php
/*
 * Created on 07/07/2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../db.inc");
include_once("../setup.php");

$turma = TURMA;
$sql = "select num_inscricao, nome, id from curso_inscricao_supervisor where curso_turma=$turma order by nome";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela curso_inscricao_supervisor");
$i = 0;
while(!$resultado->EOF) {
    $nome = $resultado->fields['nome'];
    // echo $nome . "<br>";
    $num_inscricao = $resultado->fields['num_inscricao'];
    $id   = $resultado->fields['id'];
    $resultado->MoveNext();
    $num_nome = $num_inscricao . " " . $nome;
    $supervisores[$i]["num_nome"] = $num_nome;
    $supervisores[$i]["id"] = $id;
    $i++;
}
// echo $supervisores . "<br>";
$smarty = new Smarty_estagio;
$smarty->assign("supervisores",$supervisores);
$smarty->display("supervisores_seleciona.tpl");

exit;

?>
