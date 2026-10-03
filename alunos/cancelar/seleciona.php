<?php

include_once(__DIR__ . "/../../autentica.inc");
include_once(__DIR__ . "/../../libphp/models.php");

$smarty = new Smarty_estagio;

$alunos = Aluno::all();

$i = 0;
foreach ($alunos as $aluno) {
    $alunos[$i]["aluno_id"] = $aluno->id;
    $alunos[$i]["registro"] = $aluno->registro;
    $alunos[$i]["nome"]     = $aluno->nome;
    $i++;
}

$smarty->assign("alunos",$alunos);
$smarty->display("alunos-cancelar_seleciona.tpl");

?>