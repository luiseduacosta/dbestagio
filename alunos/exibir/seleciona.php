<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$smarty = new Smarty_estagio;

$alunos = Aluno::all(array("order" => "nome"));
if (!$alunos) {
    $smarty->assign("mensagem", "Não foi possível consultar a tabela alunos.");
    $smarty->display("erro.tpl");
    exit;
}

$i = 0;
foreach ($alunos as $aluno) {
    $alunos[$i]["aluno_id"] = $aluno->id;
    $alunos[$i]["registro"] = $aluno->registro;
    $alunos[$i]["nome"]     = $aluno->nome;
    $i++;
}

$smarty->assign("alunos",$alunos);
$smarty->display("alunos-exibir_seleciona.tpl");

?>
