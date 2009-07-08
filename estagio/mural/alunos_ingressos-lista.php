<?php

include_once("../autoriza.inc");
include_once("../setup.php");

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : nome;
$turma = isset($_REQUEST['turma']) ? $_REQUEST['turma'] : NULL;
$turno = isset($_REQUEST['turno']) ? $_REQUEST['turno'] : NULL;

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
	
	// Calculo o temo de demora em realizar o curso
	if ($alunos[$i]['periodo_tcc']) { 
		$tempo0 = explode("-",$alunos[$i]['intro_seso']);
		$tempo_inicial = $tempo0[0];
		$tempo1 = explode("-",$alunos[$i]['periodo_tcc']);
		$tempo_final = $tempo1[0];
		$tempo_total = $tempo_final-$tempo_inicial;
		// echo "<br>";
		// echo $tempo_total*2 . "<br>";
	}
	
	// echo $registro . " " . $nome . ": Nivel :" . $nivel . ": Id aluno novo :" . $id_registro . "<br>";

	$criterio[] = $alunos[$i][$ordem];

	$i++;
	$res->MoveNext();
}

// Ordeno a tabela pela variavel ordem
if (isset($criterio)) array_multisort($criterio, SORT_ASC, $alunos);

// Periodos
$sql_periodo = "select distinct periodo from alunos_ingresso order by periodo";
$resultado_periodo = $db->Execute($sql_periodo);
if($resultado_periodo === false) die ("N�o foi poss�vel consultar a tabela alunos_ingresso");
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
$smarty->assign("periodos",$periodos);
$smarty->assign("alunos",$alunos);
$smarty->display("alunos_ingressos-lista.tpl");

?>
