<?php

include_once("../../autentica.inc");
include_once("../../setup.php");

$submit    = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : NULL;
$id_aluno  = isset($_REQUEST['id_aluno']) ? $_REQUEST['id_aluno'] : NULL;

$registro       = $_POST['registro'];
$nome           = $_POST['nome'];
$periodo        = $_POST['periodo'];
$tc             = $_POST['tc'];
$turno          = $_POST['turno'];
$nivel          = $_POST['nivel'];
$id_instituicao = $_POST['id_instituicao'];
$id_supervisor  = $_POST['id_supervisor'];
$id_professor   = $_POST['id_professor'];
$id_area        = $_POST['id_area'];
$nota           = $_POST['nota'];
$ch             = $_POST['ch'];

if($submit) {
    $sql_estagiarios  = "insert into estagiarios(id_aluno, registro, turno, nivel, tc, id_instituicao, id_supervisor, id_professor, periodo, id_area) ";
    $sql_estagiarios .= "values('$id_aluno', '$registro', '$turno', '$nivel', '$tc', '$id_instituicao', '$id_supervisor', '$id_professor','$periodo', '$id_area')";
    $resultado_insere = $db->Execute($sql_estagiarios);
    if($resultado_insere === false) die ("Não foi possível inserir o registro na tabela estagiarios");
}

// Pego esta informação para fazer a tabela dos anteriores estágios
$sql  = "SELECT estagiarios.id, estagiarios.periodo, estagiarios.tc, estagiarios.nivel, estagiarios.turno, estagiarios.id_instituicao, estagiarios.id_supervisor, estagiarios.id_professor, estagiarios.id_area, estagiarios.nota, estagiarios.ch, estagio.instituicao ";
$sql .= "FROM estagiarios, estagio ";
$sql .= "WHERE estagiarios.id_instituicao = estagio.id AND estagiarios.id_aluno=$id_aluno ";
$sql .= "ORDER BY estagiarios.periodo";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar as tabelas alunos, estagiarios, estagio");
$i = 0;
while(!$resultado->EOF) {
    $estagiarios[$i]['id']             = $resultado->fields['id'];
    $estagiarios[$i]['periodo']        = $resultado->fields['periodo'];
    $estagiarios[$i]['tc']             = $resultado->fields['tc'];
    $estagiarios[$i]['nivel']          = $resultado->fields['nivel'];
    $estagiarios[$i]['turno']          = $resultado->fields['turno'];
    $estagiarios[$i]['id_instituicao'] = $resultado->fields['id_instituicao'];
    $estagiarios[$i]['id_supervisor']  = $resultado->fields['id_supervisor'];
    $estagiarios[$i]['id_professor']   = $resultado->fields['id_professor'];
    $estagiarios[$i]['id_area']        = $resultado->fields['id_area'];
    $estagiarios[$i]['nota']           = $resultado->fields['nota'];
    $estagiarios[$i]['ch']             = $resultado->fields['ch'];
    $estagiarios[$i]['instituicao']    = $resultado->fields['instituicao'];

    if(empty($id_supervisor))
        $id_supervisor = "0";

    $sql_supervisor  = "select supervisores.id, supervisores.cress, supervisores.nome, supervisores.email ";
    $sql_supervisor .= "from supervisores ";
    $sql_supervisor .= "where supervisores.id=$id_supervisor ";
    $sql_supervisor .= "order by supervisores.nome";
    $resultado_supervisor = $db->Execute($sql_supervisor);
    while(!$resultado_supervisor->EOF) {
        $estagiarios[$i]['supervisor_nome']  = $resultado_supervisor->fields['nome'];
        $estagiarios[$i]['supervisor_cress'] = $resultado_supervisor->fields['cress'];
        $estagiarios[$i]['supervisor_email'] = $resultado_supervisor->fields['email'];
        $resultado_supervisor->MoveNext();
    }
    $resultado->MoveNext();
    $i++;
}

// Alunos
$sql_alunos = "select id, registro, nome from alunos where id='$id_aluno'";
$resultado_alunos = $db->Execute($sql_alunos);
if($resultado_alunos === false) die ("Não foi possível consultar a tabela alunos");
while(!$resultado_alunos->EOF) {
    $aluno_id       = $resultado_alunos->fields['id'];
    $aluno_registro = $resultado_alunos->fields['registro'];
    $aluno_nome     = $resultado_alunos->fields['nome'];
    $resultado_alunos->MoveNext();
}

// Capturo a informacao sobre as instituicoes
$sql = "select id, instituicao from estagio order by instituicao";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Nao foi possivel consultar a tabela estagio");
$i = 0;
while(!$resultado->EOF) {
    $instituicoes[$i]['id_instituicao'] = $resultado->fields['id'];
    $instituicoes[$i]['instituicao']    = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

// Capturo a informacao sobre os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$resultado_supervisores = $db->Execute($sql_supervisores);
if($resultado_supervisores === false) die ("Nao foi possivel consultar a tabela supervisores");
$i = 0;
while(!$resultado_supervisores->EOF) {
    $supervisores[$i]['id_supervisor'] = $resultado_supervisores->fields['id'];
    $supervisores[$i]['supervisor']    = $resultado_supervisores->fields['nome'];
    $resultado_supervisores->MoveNext();
    $i++;
}

// Capturo a informacao sobre os professores
$sql_professores = "select id, nome from professores order by nome";
$resultado_professores = $db->Execute($sql_professores);
if($resultado_professores === false) die ("Nao foi possivel consultar a tabela professores");
$i = 0;
while(!$resultado_professores->EOF) {
    $professores[$i]['id_professor'] = $resultado_professores->fields['id'];
    $professores[$i]['professor']    = $resultado_professores->fields['nome'];
    $resultado_professores->MoveNext();
    $i++;
}

// Capturo a informacao sobre as areas
$sql_areas = "select id, area from areas_estagio order by area";
$resultado_areas = $db->Execute($sql_areas);
if($resultado_areas === false) die ("Nao foi possivel consultar a tabela areas_estagio");
$i = 0;
while(!$resultado_areas->EOF) {
    $areas[$i]['id_area'] = $resultado_areas->fields['id'];
    $areas[$i]['area']    = $resultado_areas->fields['area'];
    $resultado_areas->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;

// Tabela de estagios anteriores
$smarty->assign("estagiarios",$estagiarios);
// Tabela inserir novo estágio
$smarty->assign("id_aluno",$id_aluno);
$smarty->assign("registro",$aluno_registro);
$smarty->assign("aluno_nome",$aluno_nome);
$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("supervisores",$supervisores);
$smarty->assign("professores",$professores);
$smarty->assign("areas",$areas);

$smarty->display("alunos-inserir_acrescentar_estagio.tpl");

exit;

?>