<?php

include_once("../../autentica.inc");
include_once("../../libphp/models.php");

$smarty = new Smarty_estagio;

// Usando modelo Aluno para buscar todos os alunos
$todosAlunos = Aluno::all("order by nome");
if (empty($todosAlunos)) die ("Não foi possível consultar a tabela alunos");

$i = 0;
foreach ($todosAlunos as $aluno) {
    $alunos[$i]["aluno_id"] = $aluno->id;
    $alunos[$i]["registro"] = $aluno->registro;
    $alunos[$i]["nome"]     = $aluno->nome;
    $i++;
}

$smarty->assign("alunos",$alunos);
$smarty->display("alunos-atualizar_seleciona.tpl");

?>