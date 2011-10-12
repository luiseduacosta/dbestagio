<?php

include_once("../../setup.php");
include_once("../../autentica.inc");

$id_aluno = $_GET['id_aluno'];
$erro = $_GET['erro'];

$sql  = "SELECT alunos.id, alunos.registro, alunos.nome, estagiarios.nivel, estagiarios.turno, estagiarios.id_instituicao, estagiarios.id_supervisor, estagio.instituicao ";
$sql .= "FROM alunos ";
$sql .= "left outer join estagiarios on alunos.id=estagiarios.id_aluno ";
$sql .= "left outer join estagio on estagiarios.id_instituicao=estagio.id ";
$sql .= "where alunos.id=$id_aluno";
// echo $sql . "<br>";
$resultado =$db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar as tabelas alunos, estagiarios, estagio");
while (!$resultado->EOF) {
    $id              = $resultado->fields['id'];
    $registro        = $resultado->fields['registro'];
    $nome            = $resultado->fields['nome'];
    $nivel           = $resultado->fields['nivel'];
    $turno           = $resultado->fields['turno'];
    $id_instituicao  = $resultado->fields['id_instituicao'];
    $id_supervisor   = $resultado->fields['id_supervisor'];
    $instituicao     = $resultado->fields['instituicao'];

    if (empty($id_supervisor))
        $id_supervisor = "0";

    $sql_supervisor  = "SELECT supervisores.id, supervisores.cress, supervisores.nome, supervisores.email ";
    $sql_supervisor .= "FROM supervisores ";
    $sql_supervisor .= "WHERE supervisores.id=$id_supervisor";
    // $sql_supervisor .= "ORDER by supervisores.nome";
    $resultado_supervisor = $db->Execute($sql_supervisor);
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
$smarty->assign("id_aluno",$id_aluno);
$smarty->assign("aluno",$nome);
$smarty->assign("registro",$registro);
$smarty->assign("nome",$nome);
$smarty->assign("nivel",$nivel);
$smarty->assign("turno",$turno);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("id_supervisor",$id_supervisor);
$smarty->assign("supervisor",$supervisor_nome);
$smarty->assign("erro",$erro);

$smarty->display("alunos-cancelar_ver_cancela.tpl");

exit;

?>
