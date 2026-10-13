<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$alunos = Aluno::all(array("order" => "nome"));
if (!$alunos) {
    $smarty = new Smarty_estagio;
    $smarty->assign("mensagem", "Não foi possível consultar a tabela alunos.");
    $smarty->display("erro.tpl");
    exit;
}
$i = 1;
$aluno_super = array();
foreach ($alunos as $aluno) {
    $aluno_super[$i]['aluno_id']       = $aluno->id;
    $aluno_super[$i]['registro']       = $aluno->registro;
    $aluno_super[$i]['nome']           = $aluno->nome;
    $aluno_super[$i]['aluno']          = $aluno->nome;
    $aluno_super[$i]['supervisor_id']  = $aluno->supervisor_id;
    $aluno_super[$i]['instituicao_id'] = $aluno->instituicao_id;
    
    $aluno_super[$i]['cress']      = '';
    $aluno_super[$i]['supervisor'] = '';

    $supervisor_id = $aluno->supervisor_id;
    if (!empty($supervisor_id)) {
        $supervisor_obj = Supervisor::find($supervisor_id);
        if ($supervisor_obj) {
            $aluno_super[$i]['cress']      = $supervisor_obj->cress;
            $aluno_super[$i]['supervisor'] = $supervisor_obj->nome;
        }
    }
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("pagina", isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '');
$smarty->assign("alunos_supervisor", $aluno_super);
$smarty->display("alunos-exibir_aluno_supervisor.tpl");

?>
