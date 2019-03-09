<?php

if ($debug == 1)
	echo $_SERVER['PHP_SELF'] . "<br>";

include_once("../../setup.php");
include_once("../../autentica.inc");

$id_aluno = $_REQUEST['id_aluno'];

$registro = $_POST['registro'];
$nome     = $_POST['nome'];
$telefone = $_POST['telefone'];
$celular  = $_POST['celular'];
$email    = $_POST['email'];

$periodo        = $_POST['periodo'];
$turno          = $_POST['turno'];
$nivel          = $_POST['nivel'];
$id_instituicao = $_POST['id_instituicao'];
$id_supervisor  = $_POST['id_supervisor'];

// Pego esta informacao para fazer a tabela dos anteriores estagios
$sql  = "SELECT estagiarios.id, estagiarios.periodo, estagiarios.nivel, estagiarios.turno, estagiarios.id_instituicao, estagiarios.id_supervisor, estagio.instituicao ";
$sql .= "FROM estagiarios, estagio ";
$sql .= "WHERE estagiarios.id_instituicao = estagio.id AND estagiarios.id_aluno=$id_aluno ";
$sql .= "ORDER BY estagiarios.periodo";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar as tabelas alunos, estagiarios, estagio");
$i = 0;
while (!$resultado->EOF) {
    $estagiarios[$i]['id']             = $resultado->fields['id'];
    $estagiarios[$i]['periodo']        = $resultado->fields['periodo'];
    $estagiarios[$i]['nivel']          = $resultado->fields['nivel'];   
    $estagiarios[$i]['turno']          = $resultado->fields['turno']; 
    $estagiarios[$i]['id_instituicao'] = $resultado->fields['id_instituicao'];
    $estagiarios[$i]['id_supervisor']  = $resultado->fields['id_supervisor'];
    $estagiarios[$i]['instituicao']    = $resultado->fields['instituicao'];

    if (empty($id_supervisor))
        $id_supervisor = "0";

    $sql_supervisor  = "select supervisores.id, supervisores.cress, supervisores.nome, supervisores.email ";
    $sql_supervisor .= "from supervisores ";
    $sql_supervisor .= "where supervisores.id=$id_supervisor ";
    $sql_supervisor .= "order by supervisores.nome";
    $resultado_supervisor = $db->Execute($sql_supervisor);
    while (!$resultado_supervisor->EOF) {
        $estagiarios[$i]['supervisor_nome']  = $resultado_supervisor->fields['nome'];
        $estagiarios[$i]['supervisor_cress'] = $resultado_supervisor->fields['cress'];
        $estagiarios[$i]['supervisor_email'] = $resultado_supervisor->fields['email'];
        $resultado_supervisor->MoveNext();
    }
    $resultado->MoveNext();
    $i++;
}

// Capturo a informacao sobre os alunos
$sql_alunos = "select id, registro, nome, telefone, celular, email from alunos where id='$id_aluno'";
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
$sql = "select id, instituicao from estagio order by instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela estagio");
$i = 0;
while (!$resultado->EOF) {
    $instituicoes[$i]['id_instituicao'] = $resultado->fields['id'];
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
    $supervisores[$i]['id_supervisor'] = $resultado_supervisores->fields['id'];
    $supervisores[$i]['supervisor'] = $resultado_supervisores->fields['nome'];
    $resultado_supervisores->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;

// Tabela de estagios anteriores
$smarty->assign("estagiarios",$estagiarios);
// Tabela inserir novo estagio
$smarty->assign("id_aluno",$id_aluno);
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