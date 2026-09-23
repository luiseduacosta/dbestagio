<?php

include_once("../../autentica.inc");

$submit   = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : NULL;
$aluno_id = isset($_REQUEST['aluno_id']) ? $_REQUEST['aluno_id'] : NULL;

$registro       = $_POST['registro'];
$nome           = $_POST['nome'];
$periodo        = $_POST['periodo'];
$tc             = $_POST['tc'];
$turno          = $_POST['turno'];
$nivel          = $_POST['nivel'];
$instituicao_id = $_POST['instituicao_id'];
$supervisor_id  = $_POST['supervisor_id'];
$professor_id   = $_POST['professor_id'];
$nota           = $_POST['nota'];
$ch             = $_POST['ch'];

if ($submit) {
    $sql_estagiarios  = "insert into estagiarios(aluno_id, registro, nivel, tc, instituicao_id, supervisor_id, professor_id, periodo) ";
    $sql_estagiarios .= "values('$aluno_id', '$registro', '$nivel', '$tc', '$instituicao_id', '$supervisor_id', '$professor_id','$periodo')";
    $resultado_insere = $db->Execute($sql_estagiarios);
    if ($resultado_insere === false) die ("Não foi possível inserir o registro na tabela estagiarios");
}

// Pego esta informação para fazer a tabela dos anteriores estágios
$estagiarios = array();
$sql  = "SELECT e.id, e.periodo, e.tc, e.nivel, e.instituicao_id, e.supervisor_id, e.professor_id, e.nota, e.ch, i.instituicao ";
$sql .= "FROM estagiarios e, instituicoes i ";
$sql .= "WHERE e.instituicao_id = i.id AND e.aluno_id = " . (int)$aluno_id . " ";
$sql .= "ORDER BY e.periodo";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar as tabelas alunos, estagiarios, instituicoes");
$i = 0;
while (!$resultado->EOF) {
    $estagiarios[$i]['id']             = $resultado->fields['id'];
    $estagiarios[$i]['periodo']        = $resultado->fields['periodo'];
    $estagiarios[$i]['tc']             = $resultado->fields['tc'];
    $estagiarios[$i]['nivel']          = $resultado->fields['nivel'];
    $estagiarios[$i]['instituicao_id'] = $resultado->fields['instituicao_id'];
    $estagiarios[$i]['supervisor_id']  = $resultado->fields['supervisor_id'];
    $estagiarios[$i]['professor_id']   = $resultado->fields['professor_id'];
    $estagiarios[$i]['nota']           = $resultado->fields['nota'];
    $estagiarios[$i]['ch']             = $resultado->fields['ch'];
    $estagiarios[$i]['instituicao']    = $resultado->fields['instituicao'];

    $current_supervisor_id = $resultado->fields['supervisor_id'];
    if (empty($current_supervisor_id))
        $current_supervisor_id = "0";

    $sql_supervisor  = "select supervisores.id, supervisores.cress, supervisores.nome, supervisores.email ";
    $sql_supervisor .= "from supervisores ";
    $sql_supervisor .= "where supervisores.id=$current_supervisor_id ";
    $sql_supervisor .= "order by supervisores.nome";
    $resultado_supervisor = $db->Execute($sql_supervisor);
    while ($resultado_supervisor && !$resultado_supervisor->EOF) {
        $estagiarios[$i]['supervisor_nome']  = $resultado_supervisor->fields['nome'];
        $estagiarios[$i]['supervisor_cress'] = $resultado_supervisor->fields['cress'];
        $estagiarios[$i]['supervisor_email'] = $resultado_supervisor->fields['email'];
        $resultado_supervisor->MoveNext();
    }
    $resultado->MoveNext();
    $i++;
}

// Alunos
$aluno_registro = '';
$aluno_nome = '';
$sql_alunos = "select id, registro, nome from alunos where id='$aluno_id'";
$resultado_alunos = $db->Execute($sql_alunos);
if ($resultado_alunos === false) die ("Não foi possível consultar a tabela alunos");
while (!$resultado_alunos->EOF) {
    $aluno_registro = $resultado_alunos->fields['registro'];
    $aluno_nome     = $resultado_alunos->fields['nome'];
    $resultado_alunos->MoveNext();
}

// Capturo a informacao sobre as instituicoes
$instituicoes = array();
$sql = "select id, instituicao from instituicoes order by instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela instituicoes");
$i = 0;
while (!$resultado->EOF) {
    $instituicoes[$i]['instituicao_id'] = $resultado->fields['id'];
    $instituicoes[$i]['instituicao']    = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

// Capturo a informacao sobre os supervisores
$supervisores = array();
$sql_supervisores = "select id, nome from supervisores order by nome";
$resultado_supervisores = $db->Execute($sql_supervisores);
if ($resultado_supervisores === false) die ("Nao foi possivel consultar a tabela supervisores");
$i = 0;
while (!$resultado_supervisores->EOF) {
    $supervisores[$i]['supervisor_id'] = $resultado_supervisores->fields['id'];
    $supervisores[$i]['supervisor']    = $resultado_supervisores->fields['nome'];
    $resultado_supervisores->MoveNext();
    $i++;
}

// Capturo a informacao sobre os professores
$professores = array();
$sql_professores = "select id, nome from professores order by nome";
$resultado_professores = $db->Execute($sql_professores);
if ($resultado_professores === false) die ("Nao foi possivel consultar a tabela professores");
$i = 0;
while (!$resultado_professores->EOF) {
    $professores[$i]['professor_id'] = $resultado_professores->fields['id'];
    $professores[$i]['professor']    = $resultado_professores->fields['nome'];
    $resultado_professores->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;

// Tabela de estagios anteriores
$smarty->assign("estagiarios",$estagiarios);
// Tabela inserir novo estágio
$smarty->assign("aluno_id",$aluno_id);
$smarty->assign("registro",$aluno_registro);
$smarty->assign("aluno_nome",$aluno_nome);
$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("supervisores",$supervisores);
$smarty->assign("professores",$professores);

$smarty->display("alunos-inserir_acrescentar_estagio.tpl");

exit;

?>