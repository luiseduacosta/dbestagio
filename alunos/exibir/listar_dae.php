<?php

include_once("../../autentica.inc");

$origem = isset($_REQUEST['origem']) ? $_REQUEST['origem'] : '';
if (empty($origem))
$origem = $_SERVER['HTTP_REFERER'];

// Verifico se o usuario esta logado (cookie correto é "usuario").
$logado = 0;
if (!empty($_COOKIE['usuario'])) {
	$logado = 1;
}

// Whitelist de colunas permitidas para ORDER BY (proteção contra SQL injection).
$ordem = @$_REQUEST["ordem"];
$ordenacoes = array(
	'nome'       => 'nome',
	'nome_desc'  => 'nome DESC',
	'registro'   => 'alunos.registro',
	'instituicao'=> 'instituicao',
	'periodo'    => 'estagiarios.periodo',
	'periodo_desc'=> 'estagiarios.periodo DESC',
	'nivel'      => 'estagiarios.nivel',
);
if (empty($ordem) || !isset($ordenacoes[$ordem])) {
	$ordem = 'nome';
}
$orderby = $ordenacoes[$ordem];

$turma = isset($_REQUEST['turma']) ? trim($_REQUEST['turma']) : NULL;

$sql  = "select nome, alunos.registro, estagiarios.nivel, alunos.cpf, alunos.identidade, alunos.nascimento, alunos.orgao, alunos.endereco, alunos.bairro, alunos.municipio, alunos.cep, alunos.codigo_telefone, alunos.telefone, alunos.codigo_celular, alunos.celular, alunos.email, instituicoes.id as instituicao_id, instituicoes.instituicao, instituicoes.seguro ";
$sql .= " from alunos ";
$sql .= " inner join estagiarios on alunos.registro = estagiarios.registro ";
$sql .= " inner join instituicoes on estagiarios.instituicao_id = instituicoes.id ";

$param = array();
if ($turma) {
	$sql .= " where estagiarios.periodo = ? ";
	$param[] = $turma;
} else {
	$sql .= " where estagiarios.periodo = (select max(estagiarios.periodo) as max_periodo from estagiarios)";
}
$sql .= " order by $orderby";

$resultado = $db->Execute($sql, $param);
if ($resultado == false) die ("Não foi possível consultar as tabelas alunos, estagiarios e instituicoes");
$i = 0;
while (!$resultado->EOF) {
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
	// echo "id " . $resultado->fields['instituicao_id'];
	$dae[$i]['instituicao_id'] = $resultado->fields['instituicao_id'];
	$dae[$i]['instituicao'] = $resultado->fields['instituicao'];
	$dae[$i]['seguro'] = $resultado->fields['seguro'];
	$resultado->MoveNext();
	$i++;
}

// Pego a informacao sobre as turma de alunos
$sql_turma = "select id, periodo from estagiarios group by periodo";
// echo $sql_turma . "<br>";
$res_turma = $db->Execute($sql_turma);
if ($res_turma === false) die ("Não foi possível consultar a tabela estagiarios");
while (!$res_turma->EOF) {
	$periodos[] = $res_turma->fields['periodo'];
	$res_turma->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("dae",$dae);
$smarty->assign("turma",$turma);
$smarty->assign("periodos",$periodos);
$smarty->display("alunos-exibir_listar_dae.tpl");

?>