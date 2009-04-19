<?php

include_once("../autoriza.inc");
require("../setup.php");

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : 'nome';
$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : PERIODO_ANTERIOR;

// Ajusto os periodos para consultar a tabela de estagiarios
$periodo_atual = $periodo;
$_periodo_atual = explode("-",$periodo_atual);
if ($_periodo_atual[1] == 2) $periodo_proximo = $_periodo_atual[0] + 1 . "-1";
if ($_periodo_atual[1] == 1) $periodo_proximo = $_periodo_atual[0] . "-2";

$sql_periodo = "select periodo from estagiarios group by periodo";
$resultado_periodo = $db->Execute($sql_periodo);
if($resultado_periodo === false) die ("Não foi possível consultar a tabela estagiarios");
$i = 0;
while (!$resultado_periodo->EOF) {
	$periodos[$i]['periodo'] = $resultado_periodo->fields['periodo'];
	$i++;
	$resultado_periodo->MoveNext();
}

$sql = "select id_aluno, alunos.registro, alunos.nome, telefone, celular, alunos.email, alunos.observacoes, estagiarios.periodo, estagiarios.nivel, estagiarios.tc_solicitacao from estagiarios inner join alunos on estagiarios.registro = alunos.registro where nivel != 4 and periodo = '$periodo_atual' group by id_aluno order by $ordem";
// echo "$sql <br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar as tabelas alunos, estagiarios");
$i = 0;
while (!$resultado->EOF) {

	$alunos[$i][$ordem] = $resultado->fields['$ordem'];
	$alunos[$i]['id_aluno'] = $resultado->fields['id_aluno'];
	$alunos[$i]['registro'] = $resultado->fields['registro'];
	$alunos[$i]['nome'] = $resultado->fields['nome'];
	$alunos[$i]['telefone'] = $resultado->fields['telefone'];
	$alunos[$i]['celular'] = $resultado->fields['celular'];
	$alunos[$i]['email'] = $resultado->fields['email'];
	$alunos[$i]['nivel'] = $resultado->fields['nivel'];
	$alunos[$i]['periodo'] = $resultado->fields['periodo'];	
	$alunos[$i]['observacoes'] = $resultado->fields['observacoes'];
	
	$registro = $resultado->fields['registro'];
	$sql_tc = "select tc_solicitacao, tc from estagiarios where registro = '$registro' and periodo ='$periodo_proximo'";
	// echo "$sql_tc <br>";
	$resultado_tc = $db->Execute($sql_tc);
	if ($resultado_tc === false) die ("Não foi possível consultar a tabela estagiarios");
	
	$tc_solicitacao = $resultado_tc->fields['tc_solicitacao'];
	// if ($tc_solicitacao == 0) $tc_solicitacao = "";
	$tc_solicitacao = ($tc_solicitacao == 0) ? NULL : $tc_solicitacao;
	$alunos[$i]['tc_solicitacao'] = $tc_solicitacao;
	$alunos[$i]['tc'] = $resultado_tc->fields['tc'];
	
	// echo "<br>";
	
	$sql_mural = "select id from mural_inscricao where id_aluno='$registro' and periodo='$periodo_proximo'";
	// echo $sql_mural . "<br>";
	$res_mural = $db->Execute($sql_mural);
	if ($res_mural === false) die ("Não foi possível consultar a tabela mural_inscricao");
	$aluno_mural = $res_mural->fields['id'];
	$alunos[$i]['mural'] = $aluno_mural;
		
	$i++;

	$resultado->MoveNext();
}

if ($alunos) {
	reset($alunos);
	sort($alunos);
}

$smarty = new Smarty_estagio;
$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("periodo",$periodo);
$smarty->assign("periodos",$periodos);
$smarty->assign("alunos",$alunos);
$smarty->display("termo_controle_entrega.tpl");

?>