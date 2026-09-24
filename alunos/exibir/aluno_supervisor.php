<?php

include_once("../../autentica.inc");

$sql  = "select a.id, a.registro, a.nome, e.nivel, e.supervisor_id, e.instituicao_id ";
$sql .= "from alunos left join estagiarios on e.aluno_id = a.id order by a.nome";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela alunos");
$i = 1;
while (!$resultado->EOF) {
    $aluno_super[$i]['aluno_id']       = $resultado->fields['id'];
    $aluno_super[$i]['registro']       = $resultado->fields['registro'];
    $aluno_super[$i]['nome']           = $resultado->fields['nome'];
    $aluno_super[$i]['supervisor_id']  = $resultado->fields['supervisor_id'];
    $aluno_super[$i]['instituicao_id'] = $resultado->fields['instituicao_id'];
    
    $supervisor_id = $resultado->fields['supervisor_id'];
    $resultado->MoveNext();

    if (empty($supervisor_id))
        $supervisor_id = "0";

    $sql_supervisores = "select cress, nome from supervisores where id=$supervisor_id";
    $resultado_supervisores = $db->Execute($sql_supervisores);
    if ($resultado_supervisores === false) die ("Não foi possível consultar a tabela supervisores");
    while (!$resultado_supervisores->EOF) {
        $aluno_super[$i]['cress']      = $resultado_supervisores->fields['cress'];
        $aluno_super[$i]['supervisor'] = $resultado_supervisores->fields['nome'];
        $resultado_supervisores->MoveNext();
    }
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("pagina",$PHP_SELF);
$smarty->assign("logado",$logado);
$smarty->assign("alunos_supervisor",$aluno_super);
$smarty->display("alunos-exibir_aluno_supervisor.tpl");

?>
