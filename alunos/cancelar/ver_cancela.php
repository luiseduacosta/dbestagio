<?php

include_once("../../autentica.inc");

$aluno_id = $_GET['aluno_id'];
$erro = $_GET['erro'];

$sql  = "SELECT alunos.id, alunos.registro, alunos.nome, estagiarios.nivel, estagiarios.instituicao_id, estagiarios.supervisor_id, instituicoes.instituicao ";
$sql .= "FROM alunos ";
$sql .= "left outer join estagiarios on alunos.id=estagiarios.aluno_id ";
$sql .= "left outer join instituicoes on estagiarios.instituicao_id=instituicoes.id ";
$sql .= "where alunos.id=$aluno_id";
// echo $sql . "<br>";
$resultado =$db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar as tabelas alunos, estagiarios, instituicoes");
while (!$resultado->EOF) {
    $id              = $resultado->fields['id'];
    $registro        = $resultado->fields['registro'];
    $nome            = $resultado->fields['nome'];
    $nivel           = $resultado->fields['nivel'];
    $turno           = NULL;
    $instituicao_id  = $resultado->fields['instituicao_id'];
    $supervisor_id   = $resultado->fields['supervisor_id'];
    $instituicao     = $resultado->fields['instituicao'];

    if (empty($supervisor_id))
        $supervisor_id = "0";

    $sql_supervisor  = "SELECT supervisores.id, supervisores.cress, supervisores.nome, supervisores.email ";
    $sql_supervisor .= "FROM supervisores ";
    $sql_supervisor .= "WHERE supervisores.id=$supervisor_id";
    // $sql_supervisor .= "ORDER by supervisores.nome";
    $resultado_supervisor = $db->Execute($sql_supervisor);
    $supervisor_nome = '';
    $supervisor_cress = '';
    $supervisor_email = '';
    while (!$resultado_supervisor->EOF) {
        $supervisor_nome  = $resultado_supervisor->fields['nome'];
        $supervisor_cress = $resultado_supervisor->fields['cress'];
        $supervisor_email = $resultado_supervisor->fields['email'];
        $resultado_supervisor->MoveNext();
    }
    $resultado->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("pagina",$PHP_SELF);
$smarty->assign("aluno_id",$aluno_id);
$smarty->assign("aluno",$nome);
$smarty->assign("registro",$registro);
$smarty->assign("nome",$nome);
$smarty->assign("nivel",$nivel);
$smarty->assign("turno",$turno);
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("supervisor_id",$supervisor_id);
$smarty->assign("supervisor",$supervisor_nome);
$smarty->assign("erro",$erro);

$smarty->display("alunos-cancelar_ver_cancela.tpl");

exit;

?>
