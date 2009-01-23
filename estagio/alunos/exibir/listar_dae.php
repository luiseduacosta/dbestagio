<?php

include_once("../../db.inc");
include_once("../../setup.php");
include_once("../../autentica.inc");

$origem = $_REQUEST['origem'];
if(empty($origem))
    $origem = $_SERVER['HTTP_REFERER'];

// Verifico se o usuario esta logado
if(isset($_REQUEST['usuario_senha'])) {
    $usuario = $_REQUEST['usuario_senha'];
    if ($usuario) 
	$logado = 1;
}

$ordem = @$_REQUEST["ordem"];
if (empty($ordem))
    $ordem = "nome";

$sql  = "select nome, alunos.registro, estagiarios.nivel, alunos.cpf, alunos.identidade, alunos.nascimento, alunos.orgao, alunos.endereco, alunos.bairro, alunos.municipio, alunos.cep, alunos.codigo_telefone, alunos.telefone, alunos.codigo_celular, alunos.celular, alunos.email, instituicao ";
$sql .= " from alunos ";
$sql .= " inner join estagiarios on alunos.registro = estagiarios.registro ";
$sql .= " inner join estagio on estagiarios.id_instituicao = estagio.id ";
$sql .= " where estagiarios.periodo = (select max(estagiarios.periodo) as max_periodo from estagiarios)";
$sql .= " order by $ordem";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado == false) die ("N�o foi poss�vel consultar as tabelas alunos, estagiarios e estagio");
$i = 0;
while(!$resultado->EOF) {
	$dae[$i]['nome'] = $resultado->fields['nome'];
	$dae[$i]['registro'] = $resultado->fields['registro'];
	$dae[$i]['nivel'] = $resultado->fields['nivel'];
	$dae[$i]['endereco'] = $resultado->fields['endereco'];
	$dae[$i]['bairro'] = $resultado->fields['bairro'];
	$dae[$i]['municipio'] = $resultado->fields['municipio'];
	$dae[$i]['cep'] = $resultado->fields['cep'];
	$dae[$i]['identidade'] = $resultado->fields['identidade'];
	$dae[$i]['nascimento'] = date('d-m-Y',strtotime($resultado->fields['nascimento']));
	$dae[$i]['orgao'] = $resultado->fields['orgao'];
	$dae[$i]['cpf'] = $resultado->fields['cpf'];
	$dae[$i]['codigo_telefone'] = $resultado->fields['codigo_telefone'];
	$dae[$i]['telefone'] = $resultado->fields['telefone'];
	$dae[$i]['codigo_celular'] = $resultado->fields['codigo_celular'];
	$dae[$i]['celular'] = $resultado->fields['celular'];
	$dae[$i]['email'] = $resultado->fields['email'];
	$dae[$i]['instituicao'] = $resultado->fields['instituicao'];
	$resultado->MoveNext();	
	$i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("dae",$dae);
$smarty->display("alunos-exibir_listar_dae.tpl");

?>