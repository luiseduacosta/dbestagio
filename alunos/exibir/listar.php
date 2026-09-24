<?php

require_once("../../autentica.inc");

$sqlUltimoPeriodo = "select max(periodo) as ultimoPeriodo from estagiarios";
$resultadoMaxPeriodo = $db->Execute($sqlUltimoPeriodo);
if ($resultadoMaxPeriodo === false) die ("Não foi possível consultar a tabela estagiarios");
$ultimoPeriodo = $resultadoMaxPeriodo->fields['ultimoPeriodo'] ?? '';

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : 'nome';

$seleciona_nivel = isset($_REQUEST['seleciona_nivel']) ? $_REQUEST['seleciona_nivel'] : '0';
$seleciona_turno = isset($_REQUEST['seleciona_turno']) ? $_REQUEST['seleciona_turno'] : '0';
$area_id = isset($_REQUEST['area_id']) ? $_REQUEST['area_id'] : '0';
$seleciona_instituicao = isset($_REQUEST['seleciona_instituicao']) ? $_REQUEST['seleciona_instituicao'] : '0';
$seleciona_periodo = isset($_REQUEST['seleciona_periodo']) ? $_REQUEST['seleciona_periodo'] : $ultimoPeriodo;
$seleciona_professor = isset($_REQUEST['seleciona_professor']) ? $_REQUEST['seleciona_professor'] : '0';

$sql1 = "select estagiarios.aluno_id, " .
"alunos.registro, ".
"alunos.nome, ".
"alunos.telefone, ".
"alunos.celular, ".
"alunos.email, ".
"estagiarios.instituicao_id, ".
"estagiarios.id, ".
"estagiarios.tc, ".
"estagiarios.tc_solicitacao, ".
"estagiarios.nivel, ".
"estagiarios.periodo, ".
"estagiarios.nota, ".
"estagiarios.ch, ".
"instituicoes.id as instituicao_id, ".
"instituicoes.instituicao, ".
"supervisores.id as supervisor_id, ".
"supervisores.nome as nomeSupervisor, ".
"professores.nome as nomeProfessor, ".
"professores.id as professor_id " .        
" from estagiarios inner join alunos ".
"on estagiarios.aluno_id=alunos.id ".
" left outer join instituicoes ".
"on estagiarios.instituicao_id=instituicoes.id ".
" left outer join supervisores ".
"on estagiarios.supervisor_id=supervisores.id ".
" left outer join professores ".
"on estagiarios.professor_id=professores.id " ;

if (empty($seleciona_nivel) || $seleciona_nivel === '0') {
	$sql3 = " where nivel > '0'";
} else {
	$sql3 = " where nivel = '" . addslashes($seleciona_nivel) . "' " ;
}

if (!empty($seleciona_instituicao) && $seleciona_instituicao !== '0') {
	$sql3 .= " and estagiarios.instituicao_id = '" . addslashes($seleciona_instituicao) . "' ";
}
if (!empty($seleciona_periodo) && $seleciona_periodo !== '0') {
	$sql3 .= " and periodo = '" . addslashes($seleciona_periodo) . "' ";
}
if (!empty($seleciona_professor) && $seleciona_professor !== '0') {
	$sql3 .= " and estagiarios.professor_id = '" . addslashes($seleciona_professor) . "' ";
}

$sql = $sql1 . $sql3;
$resultadoLista = $db->Execute($sql);
if ($resultadoLista === false) die ("Nao foi possivel consultar as tabelas estagiarios, alunos");

$estagiarios = array();
$codigo_0 = 0;
$codigo_1 = 0;
$codigo_2 = 0;
$codigo_3 = 0;
$codigo_4 = 0;
$codigo_5 = 0;
$codigo_6 = 0;
$codigo_7 = 0;

$i=0;
while (!$resultadoLista->EOF) {

	$estagiarios[$i]['estagiario_id']  = $resultadoLista->fields['id'];
	$estagiarios[$i]['aluno_id']       = $resultadoLista->fields['aluno_id'];
	$estagiarios[$i]['registro']       = $resultadoLista->fields['registro'];
	$estagiarios[$i]['tc']             = $resultadoLista->fields['tc'];
	$estagiarios[$i]['tc_solicitacao'] = $resultadoLista->fields['tc_solicitacao'];
	$estagiarios[$i]['nome']           = $resultadoLista->fields['nome'];
	$estagiarios[$i]['email']          = strtolower($resultadoLista->fields['email'] ?? '');
	$estagiarios[$i]['celular']        = $resultadoLista->fields['celular'];
	$estagiarios[$i]['telefone']       = $resultadoLista->fields['telefone'];
	$estagiarios[$i]['nivel'] 	       = $resultadoLista->fields['nivel'];
	$estagiarios[$i]['periodo']        = $resultadoLista->fields['periodo'];
	$estagiarios[$i]['nota']           = $resultadoLista->fields['nota'];
	$estagiarios[$i]['ch']             = $resultadoLista->fields['ch'];
	$estagiarios[$i]['instituicao_id'] = $resultadoLista->fields['instituicao_id'];
	$estagiarios[$i]['instituicao']    = $resultadoLista->fields['instituicao'];
	$estagiarios[$i]['seguro']         = $resultadoLista->fields['seguro'] ?? '';
	$estagiarios[$i]['supervisor_id']  = $resultadoLista->fields['supervisor_id'];
	$estagiarios[$i]['supervisor']     = $resultadoLista->fields['nomeSupervisor'];
	$estagiarios[$i]['professor_id']   = $resultadoLista->fields['professor_id'];
	$estagiarios[$i]['professor']      = $resultadoLista->fields['nomeProfessor'];
	$estagiarios[$i]['codigo']         = '';
	$estagiarios[$i]['area']           = '';

	$aluno_id = $resultadoLista->fields['aluno_id'];
	$registro = $resultadoLista->fields['registro'];
	$nome     = $resultadoLista->fields['nome'];
	$nivel    = $resultadoLista->fields['nivel'];

	$sqlNivel = "select nivel, estagiarios.instituicao_id, instituicoes.instituicao, estagiarios.periodo from estagiarios " .
			" join instituicoes on instituicoes.id = estagiarios.instituicao_id " .
			" where aluno_id = " . (int)$aluno_id;
	$resultadoNivel = $db->Execute($sqlNivel);
	if ($resultadoNivel === false) die ("Nao foi possivel consultar esta tabela estagiarios");
	$nivel1 = NULL;
	$nivel2 = NULL;
	$nivel3 = NULL;
	$nivel4 = NULL;
	while (!$resultadoNivel->EOF) {
		$nivelCadaAluno = $resultadoNivel->fields['nivel'];
		$instituicao_id = $resultadoNivel->fields['instituicao_id'];
		$instituicao = $resultadoNivel->fields['instituicao'];
		$periodo_nivel = $resultadoNivel->fields['periodo'];

		if ($nivelCadaAluno == 1) {
			$estagiarios[$i]['nivel1'] = $instituicao_id;
			$estagiarios[$i]['instituicao1'] = $instituicao;
			$estagiarios[$i]['periodo1'] = $periodo_nivel;
			$nivel1 = $instituicao_id;
		}

		if ($nivelCadaAluno == 2) {
			$estagiarios[$i]['nivel2'] = $instituicao_id;
			$estagiarios[$i]['instituicao2'] = $instituicao;
			$estagiarios[$i]['periodo2'] = $periodo_nivel;
			$nivel2 = $instituicao_id;
		}

		if ($nivelCadaAluno == 3) {
			$estagiarios[$i]['nivel3'] = $instituicao_id;
			$estagiarios[$i]['instituicao3'] = $instituicao;
			$estagiarios[$i]['periodo3'] = $periodo_nivel;
			$nivel3 = $instituicao_id;
		}

		if ($nivelCadaAluno == 4) {
			$estagiarios[$i]['nivel4'] = $instituicao_id;
			$estagiarios[$i]['instituicao4'] = $instituicao;
			$estagiarios[$i]['periodo4'] = $periodo_nivel;
			$nivel4 = $instituicao_id;
		}

		$resultadoNivel->MoveNext();
	}

	// Se os quatro niveis de estagio estao preenchidos
	if ((!empty($nivel1)) and (!empty($nivel2)) and (!empty($nivel3)) and (!empty($nivel4))) {
		$codigo = 0;
		if (($nivel1 == $nivel2) and ($nivel2 == $nivel3) and ($nivel3 == $nivel4)) $codigo = 0;
		if (($nivel1 != $nivel2) and ($nivel2 != $nivel3) and ($nivel3 != $nivel4)) $codigo = 1;
		if (($nivel1 == $nivel2) and ($nivel2 != $nivel3) and ($nivel3 == $nivel4)) $codigo = 2;
		if (($nivel1 == $nivel2) and ($nivel2 != $nivel3) and ($nivel3 != $nivel4)) $codigo = 3;
		if (($nivel1 != $nivel2) and ($nivel2 == $nivel3) and ($nivel3 != $nivel4)) $codigo = 4;
		if (($nivel1 != $nivel2) and ($nivel2 != $nivel3) and ($nivel3 == $nivel4)) $codigo = 5;
		if (($nivel1 == $nivel2) and ($nivel2 == $nivel3) and ($nivel3 != $nivel4)) $codigo = 6;
		if (($nivel1 != $nivel2) and ($nivel2 == $nivel3) and ($nivel3 == $nivel4)) $codigo = 7;

		if ($codigo == 0) $codigo_0++;
		if ($codigo == 1) $codigo_1++;
		if ($codigo == 2) $codigo_2++;
		if ($codigo == 3) $codigo_3++;
		if ($codigo == 4) $codigo_4++;
		if ($codigo == 5) $codigo_5++;
		if ($codigo == 6) $codigo_6++;
		if ($codigo == 7) $codigo_7++;

		$estagiarios[$i]['codigo'] = $codigo;
	}

	$i++;
	$resultadoLista->MoveNext();
}

$total = $codigo_0 + $codigo_1 + $codigo_2 + $codigo_3 + $codigo_4 + $codigo_5 + $codigo_6 + $codigo_7;

// Ordeno a tabela
if (!empty($estagiarios) && !empty($ordem)) {
	$criterio_col = array_column($estagiarios, $ordem);
	if (!empty($criterio_col)) {
		array_multisort($criterio_col, SORT_ASC, $estagiarios);
	}
}

// Pego a listagem das instituicoes ativas para formulario de select
$instituicoes = array();
$sqlInstituicao  = "select distinct instituicoes.id, instituicoes.instituicao from estagiarios " ;
$sqlInstituicao .= "left outer join instituicoes on estagiarios.instituicao_id=instituicoes.id ";
$sqlInstituicao .= "order by instituicoes.instituicao";
$res_estagio = $db->Execute($sqlInstituicao);
if ($res_estagio === false) die ("Nao foi possivel consultar a tabela instituicoes");
$i = 0;
while (!$res_estagio->EOF) {
	$instituicoes[$i]['instituicao_id'] = $res_estagio->fields['id'];
	$instituicoes[$i]['instituicao']    = $res_estagio->fields['instituicao'];
	$i++;
	$res_estagio->MoveNext();
}

// Pego a lista dos professores
$professores = array();
$sqlProfessor  = "select professores.id, professores.nome from professores ";
$sqlProfessor .= " inner join estagiarios on professores.id = estagiarios.professor_id ";
$sqlProfessor .= " group by estagiarios.professor_id ";
$sqlProfessor .= " order by professores.nome";
$res_professor = $db->Execute($sqlProfessor);
if ($res_professor === false) die ("Nao foi possivel consultar a tabela professores");
$i = 0;
while (!$res_professor->EOF) {
	$professores[$i]['professor_id'] = $res_professor->fields['id'];
	$professores[$i]['nome']         = $res_professor->fields['nome'];
	$i++;
	$res_professor->MoveNext();
}

// Pego o nome e o numero da instituicao para o cabecalho da tabela
$nome_instituicao = '';
if (!empty($seleciona_instituicao) && $seleciona_instituicao !== '0') {
	$sql_instituicao = "select id, instituicao from instituicoes where id = " . (int)$seleciona_instituicao . " order by instituicao";
	$res_instituicao = $db->Execute($sql_instituicao);
	if ($res_instituicao === false) die ("Não foi possível consultar a tabela instituicoes");
	while (!$res_instituicao->EOF) {
		$num_instituicao  = $res_instituicao->fields['id'];
		$nome_instituicao = $res_instituicao->fields['instituicao'];
		$res_instituicao->MoveNext();
	}
}

// Pego o nome e o numero do professor para o cabecalho da tabela
$nome_professor = '';
if (!empty($seleciona_professor) && $seleciona_professor !== '0') {
	$sql_professor = "select id, nome from professores where id = " . (int)$seleciona_professor . " order by nome";
	$resultado_professor = $db->Execute($sql_professor);
	if ($resultado_professor === false) die ("Nao foi possivel consultar a tabela professores");
	while (!$resultado_professor->EOF) {
		$num_professor  = $resultado_professor->fields['id'];
		$nome_professor = $resultado_professor->fields['nome'];
		$resultado_professor->MoveNext();
	}
}

// Pego os periodos para listar as instituicoes
$matriz_periodo = array();
$sql_periodo = "select distinct periodo from estagiarios order by periodo";
$resultado_periodo = $db->Execute($sql_periodo);
if ($resultado_periodo === false) die ("Não foi possível consultar a tabela estagiarios");
$i = 0;
while (!$resultado_periodo->EOF) {
	$matriz_periodo[$i]['turma'] = $resultado_periodo->fields['periodo'];
	$resultado_periodo->MoveNext();
	$i++;
}

$logado = ($usuario_ok || !empty($isAdmin)) ? 1 : 0;

$smarty = new Smarty_estagio;

$smarty->assign("ordem",$ordem);
$smarty->assign("logado",$logado);
$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("professores",$professores);
$smarty->assign("areas", array());

$smarty->assign("seleciona_turno",$seleciona_turno);
$smarty->assign("seleciona_nivel",$seleciona_nivel);
$smarty->assign("seleciona_instituicao",$seleciona_instituicao);
$smarty->assign("seleciona_professor",$seleciona_professor);
$smarty->assign("seleciona_periodo",$seleciona_periodo);

$smarty->assign("lista",$estagiarios);

$smarty->assign("nome_instituicao",$nome_instituicao);
$smarty->assign("nome_professor",$nome_professor);
$smarty->assign("area_selecionada", '');
$smarty->assign("matriz_periodo",$matriz_periodo);
$smarty->assign("periodo",$seleciona_periodo);

$smarty->assign("codigo_0",$codigo_0);
$smarty->assign("codigo_1",$codigo_1);
$smarty->assign("codigo_2",$codigo_2);
$smarty->assign("codigo_3",$codigo_3);
$smarty->assign("codigo_4",$codigo_4);
$smarty->assign("codigo_5",$codigo_5);
$smarty->assign("codigo_6",$codigo_6);
$smarty->assign("codigo_7",$codigo_7);
$smarty->assign("total",$total);

$smarty->display("alunos-exibir_listar.tpl");

exit;

?>
