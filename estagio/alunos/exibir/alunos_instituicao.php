<?php

include_once("../../db.inc");
include_once("../../setup.php");

$id_instituicao = $_GET['id_instituicao'];
$ordem = $_GET['ordem'];
$periodo = $_GET['periodo'];

if(empty($ordem))
    $ordem="nome";

$sql  = "SELECT alunos.id, alunos.registro, alunos.nome, ";
$sql .= " estagiarios.nivel, estagiarios.turno, estagiarios.periodo, ";
$sql .= " estagio.id AS id_instituicao, estagio.instituicao, ";
$sql .= " a.area, ";
$sql .= " supervisores.id as id_supervisor, supervisores.nome as supervisor";
$sql .= " FROM alunos "; 
$sql .= " left outer join estagiarios ";
$sql .= " on alunos.id = estagiarios.id_aluno ";
$sql .= " left outer join estagio ";
$sql .= " on estagio.id = estagiarios.id_instituicao ";
$sql .= " left outer join areas_estagio as a ";
$sql .= " on estagiarios.id_area = a.id ";
$sql .= " left outer join supervisores ";
$sql .= " on estagiarios.id_supervisor = supervisores.id ";
$sql .= " where estagiarios.id_instituicao='$id_instituicao' ";
if ($periodo) $sql .= " and estagiarios.periodo='$periodo' ";
$sql .= " ORDER BY $ordem";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar as tabelas alunos, estagiarios, estagio, areas_estagio");
$i = 0;
while(!$resultado->EOF) {
    $alunos[$i]['id']          = $resultado->fields['id'];
    $alunos[$i]['nome']        = $resultado->fields['nome'];
    $alunos[$i]['registro']    = $resultado->fields['registro'];
    $alunos[$i]['turno']       = $resultado->fields['turno'];
    $alunos[$i]['nivel']       = $resultado->fields['nivel'];
    $alunos[$i]['periodo']       = $resultado->fields['periodo'];    
    $alunos[$i]['instituicao'] = $resultado->fields['instituicao'];
    $alunos[$i]['area']	       = $resultado->fields['area'];
    $alunos[$i]['id_supervisor']  = $resultado->fields['id_supervisor'];
    $alunos[$i]['supervisor']  = $resultado->fields['supervisor'];
    // echo "Id super " . $alunos[$i]['id_supervisor'] . "<br>";
    $resultado->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("alunos",$alunos);
$smarty->assign("periodo",$periodo);
$smarty->display("alunos-exibir_alunos_instituicao.tpl");

exit;

?>