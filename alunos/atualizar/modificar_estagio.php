<?php

if ($debug == 1)
	echo $_SERVER['PHP_SELF'] . "<br>";

include_once("../../autentica.inc");

$aluno_id = $_REQUEST['aluno_id'];

$registro = $_POST['registro'];
$nome     = $_POST['nome'];
$telefone = $_POST['telefone'];
$celular  = $_POST['celular'];
$email    = $_POST['email'];

$periodo        = $_POST['periodo'];
$turno          = $_POST['turno'];
$nivel          = $_POST['nivel'];
$instituicao_id = $_POST['instituicao_id'];
$supervisor_id  = $_POST['supervisor_id'];

// Pego esta informacao para fazer a tabela dos anteriores estagios
$estagiarios = array();
$sql  = "SELECT e.id, e.periodo, e.nivel, e.instituicao_id, e.supervisor_id, i.instituicao ";
$sql .= "FROM estagiarios e, instituicoes i ";
$sql .= "WHERE e.instituicao_id = i.id AND e.aluno_id = " . (int)$aluno_id . " ";
$sql .= "ORDER BY e.periodo";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar as tabelas alunos, estagiarios, instituicoes");
$i = 0;
while (!$resultado->EOF) {
    $estagiarios[$i]['id']             = $resultado->fields['id'];
    $estagiarios[$i]['periodo']        = $resultado->fields['periodo'];
    $estagiarios[$i]['nivel']          = $resultado->fields['nivel'];   
    $estagiarios[$i]['instituicao_id'] = $resultado->fields['instituicao_id'];
    $estagiarios[$i]['supervisor_id']  = $resultado->fields['supervisor_id'];
    $estagiarios[$i]['instituicao']    = $resultado->fields['instituicao'];

    $current_supervisor_id = $resultado->fields['supervisor_id'];
    if (empty($current_supervisor_id))
        $current_supervisor_id = "0";

    $sql_supervisor  = "select s.id, s.cress, s.nome, s.email ";
    $sql_supervisor .= "from supervisores s ";
    $sql_supervisor .= "where s.id=$current_supervisor_id ";
    $sql_supervisor .= "order by s.nome";
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

// Capturo a informacao sobre os alunos
$aluno_registro = '';
$aluno_nome = '';
$aluno_telefone = '';
$aluno_celular = '';
$aluno_email = '';
$sql_alunos = "select id, registro, nome, telefone, celular, email from alunos where id='$aluno_id'";
$resultado_alunos = $db->Execute($sql_alunos);
if ($resultado_alunos === false) die ("Nao foi possivel consultar a tabela alunos");
while (!$resultado_alunos->EOF) {
    $aluno_id       = $resultado_alunos->fields['id'];
    $aluno_registro = $resultado_alunos->fields['registro'];
    $aluno_nome     = $resultado_alunos->fields['nome'];
    $aluno_telefone = $resultado_alunos->fields['telefone'];
    $aluno_celular  = $resultado_alunos->fields['celular'];
    $aluno_email    = $resultado_alunos->fields['email'];
    
    $resultado_alunos->MoveNext();
}

// Capturo a informacao sobre as instituicooes
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

$smarty = new Smarty_estagio;

// Tabela de estagios anteriores
$smarty->assign("estagiarios",$estagiarios);
// Tabela inserir novo estagio
$smarty->assign("aluno_id",$aluno_id);
$smarty->assign("registro",$aluno_registro);
$smarty->assign("aluno_nome",$aluno_nome);
$smarty->assign("telefone",$aluno_telefone);
$smarty->assign("celular",$aluno_celular);
$smarty->assign("email",$aluno_email);

$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("supervisores",$supervisores);

$smarty->display("alunos-atualizar_modificar_estagio.tpl");

exit;

?>