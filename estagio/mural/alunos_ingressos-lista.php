<?php

include_once("../autentica.inc");
include_once("../setup.php");

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : nome;
$turma = isset($_REQUEST['turma']) ? $_REQUEST['turma'] : NULL;
$turno = isset($_REQUEST['turno']) ? $_REQUEST['turno'] : NULL;

$periodo_atual = isset($_REQUEST['periodo_atual']) ? $_REQUEST['periodo_atual'] : NULL;

if (empty($periodo_atual)) {
	$periodo_atual = PERIODO_ATUAL;
}

if (!$turma) {
	$sql_turma = "select max(periodo) as periodo from alunos_ingresso";
	$res_turma = $db->Execute($sql_turma);
	$turma = $res_turma->fields['periodo'];
}

$sql = "select periodo, turno, registro, nome, etica from alunos_ingresso " .
		" where periodo='$turma'"; 
if ($turno) $sql .=	" and turno='$turno'";

// echo $sql . "<br>";
$res = $db->Execute($sql);

// Pego a variavel periodo_intro_seso para calcular periodo em curso
$periodo_intro_seso = $res->fields['periodo'];

$i = 0;
while(!$res->EOF) {

	$registro = $res->fields['registro'];

	$alunos[$i]['turno'] = $res->fields['turno'];
	$alunos[$i]['intro_seso'] = $res->fields['periodo'];
	$alunos[$i]['registro'] = $res->fields['registro'];
	$alunos[$i]['nome'] = $res->fields['nome'];
	// $alunos[$i]['etica'] = $res->fields['etica'];

	// Pego os alunos que cursaram etica	
	$sql_etica = "select periodo from alunos_etica where registro='$registro' order by periodo";
	// echo $sql_etica . "<br>";
	$res_etica = $db->Execute($sql_etica);
	$alunos[$i]['etica'] = $res_etica->fields['periodo'];

	// Verifico se nao fez inscricao em outro periodo
	$sql_outro = "select nome, registro, min(periodo) as periodo from alunos_ingresso where registro='$registro' and periodo !='$turma' group by periodo";
	$res_outro = $db->Execute($sql_outro);
	if ($res_outro == false) die("Nao foi possivel consultar a tabela alunos_ingresso");
	$alunos[$i]['outro_periodo'] = $res_outro->fields['periodo'];
	$outro_nome = $res_outro->fields['nome'];
	// if ($outro_periodo) echo $outro_nome . " " . $outro_periodo . "<br>";

	// Pego os alunos que estao estagiando
	$sql_estagiarios = "select periodo, registro, nivel as max_nivel from estagiarios " .
			" where registro='$registro' " .
			" order by periodo ";
	// echo $sql_estagiarios . "<br>";
	$res_estagiarios = $db->Execute($sql_estagiarios);
	$q_estagiarios = $res_estagiarios->RecordCount();
	// echo $q_estagiarios . "<br>";
	if ($q_estagiarios != 0) {
		$alunos[$i]['periodo_estagio'] = $res_estagiarios->fields['periodo'];
		$alunos[$i]['id_registro'] = $res_estagiarios->fields['registro'];

		while (!$res_estagiarios->EOF) {

			$alunos[$i]['nivel'] = $res_estagiarios->fields['max_nivel'];

			$nivel = $res_estagiarios->fields['max_nivel'];

			// echo $nivel . "<br>";
			$res_estagiarios->MoveNext();
		}
	} else {
		$sql_novos = "select alunosNovos.registro, mural_inscricao.periodo from alunosNovos " .
				" join mural_inscricao on mural_inscricao.id_aluno = alunosNovos.registro " .
				" where alunosNovos.registro='$registro'";
		// echo $sql_novos . "<br>";
		$res_novos = $db->Execute($sql_novos);
		while (!$res_novos->EOF) {
			$alunos[$i]['id_registro'] = $res_novos->fields['registro'];
			$alunos[$i]['busca_estagio'] = $res_novos->fields['periodo'];
			$res_novos->MoveNext();
		}
	}

	// Entregou monografia
	$sql_tcc = "select periodo, num_monografia from tcc_alunos " .
			" join monografia on tcc_alunos.num_monografia = monografia.codigo " .
			" where registro='$registro'";
	$res_tcc = $db->Execute($sql_tcc);
	$alunos[$i]['tcc'] = $res_tcc->fields['num_monografia'];
	$alunos[$i]['periodo_tcc'] = $res_tcc->fields['periodo'];
	
	// Calculo o tempo de demora em realizar o curso
	if ($alunos[$i]['periodo_tcc']) { 
		// Pego o periodo mais anterior
		if ($alunos[$i]['outro_periodo']) {
			if ($alunos[$i]['outro_periodo'] < $alunos[$i]['intro_seso']) {
				// echo $alunos[$i]['outro_periodo'] . "<br>";
				$tempo0 = explode("-",$alunos[$i]['outro_periodo']);
			} else {
				$tempo0 = explode("-",$alunos[$i]['intro_seso']);
			}
		} else {
			$tempo0 = explode("-",$alunos[$i]['intro_seso']);
		}
		$tempo_inicial = $tempo0[0];
		$periodo_inicial = $tempo0[1];
		$tempo1 = explode("-",$alunos[$i]['periodo_tcc']);
		$tempo_final = $tempo1[0];
		$periodo_final = $tempo1[1];
		$tempo_total = $tempo_final-$tempo_inicial;
		// echo "<br>";

		if ($periodo_inicial < $periodo_final) {
			$tempo_total = ($tempo_total * 2) + 2;
		} elseif ($periodo_inicial > $periodo_final) {
			$tempo_total = ($tempo_total * 2);
		} elseif ($periodo_inicial === $periodo_final) {
			$tempo_total = ($tempo_total * 2) + 1;
		}

		$alunos[$i]['tempo_total'] = $tempo_total;
		// echo $tempo_total . "<br>";
	}
	
	// echo $registro . " " . $nome . ": Nivel :" . $nivel . ": Id aluno novo :" . $id_registro . "<br>";

	$criterio[] = $alunos[$i][$ordem];

	$i++;
	$res->MoveNext();
}

// Ordeno a tabela pela variavel ordem
if (isset($criterio)) array_multisort($criterio, SORT_ASC, $alunos);

// Calculo o periodo atual
if ($periodo_atual) {
	$tempo0 = explode("-",$periodo_intro_seso);
	$tempo_inicial = $tempo0[0];
	$periodo_inicial = $tempo0[1];
	$tempo1 = explode("-",$periodo_atual);
	$tempo_final = $tempo1[0];
	$periodo_final = $tempo1[1];
	$tempo_cursado = ($tempo_final - $tempo_inicial);

	// echo $tempo_cursado . "<br>";

	if ($periodo_inicial < $periodo_final) {
		$tempo_cursado = ($tempo_cursado * 2) + 2;
	} elseif ($periodo_inicial > $periodo_final) {
		$tempo_cursado = ($tempo_cursado * 2);
	} elseif ($periodo_inicial === $periodo_final) {
		$tempo_cursado = ($tempo_cursado * 2) + 1;
	}

	// echo "<br>";
	// echo $tempo_cursado . "<br>";
}

// Periodos
$sql_periodo = "select distinct periodo from alunos_ingresso order by periodo";
$resultado_periodo = $db->Execute($sql_periodo);
if($resultado_periodo === false) die ("Não foi possível consultar a tabela alunos_ingresso");
$i = 0;
while(!$resultado_periodo->EOF) {
    $periodos[$i]['periodo'] = $resultado_periodo->fields['periodo'];
    $resultado_periodo->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("ordem",$ordem);
$smarty->assign("turma",$turma);
$smarty->assign("turno",$turno);
$smarty->assign("periodo_atual",$periodo_atual);
$smarty->assign("tempo_cursado",$tempo_cursado);
$smarty->assign("periodos",$periodos);
$smarty->assign("alunos",$alunos);
$smarty->display("file:".RAIZ."/estagio/mural/alunos_ingressos-lista.tpl");

?>
