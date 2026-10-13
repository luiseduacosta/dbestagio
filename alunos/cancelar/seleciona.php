<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$smarty = new Smarty_estagio;

$alunos = Aluno::all();

$i = 0;
foreach ($alunos as $aluno) {
    $alunos[$i]["aluno_id"] = $aluno->id;
    $alunos[$i]["registro"] = $aluno->registro;
    $alunos[$i]["nome"]     = $aluno->nome;
    $i++;
}

$smarty->assign("alunos", $alunos);
$smarty->assign("pagina", isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '');
$smarty->display("alunos-cancelar_seleciona.tpl");

?>