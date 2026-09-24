<?php

include_once("../../autentica.inc");

$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : NULL;
$periodo_atual = isset($_REQUEST['periodo_atual']) ? $_REQUEST['periodo_atual'] : PERIODO_ATUAL;

if (empty($periodo_atual)) {
	$periodo_atual = PERIODO_ATUAL;
}

$botao  = isset($_POST['botao']) ? $_POST['botao'] : '';
$indice = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;

$aluno_id = isset($_REQUEST['aluno_id']) ? $_REQUEST['aluno_id'] : NULL;
$registro = isset($_REQUEST['registro']) ? $_REQUEST['registro'] : NULL;

if (empty($aluno_id) && !empty($registro)) {
	$sql = "select id from alunos where registro = " . (int)$registro;
	$resultado = $db->Execute($sql);
	if ($resultado && !$resultado->EOF) {
		$aluno_id = $resultado->fields['id'];
	}
}

$sql  = "SELECT a.id, a.registro, a.nome, a.ingresso, a.codigo_telefone, a.telefone, a.codigo_celular, a.celular, a.email, ";
$sql .= " a.cpf, a.identidade, a.orgao, a.nascimento, a.endereco, a.cep, a.municipio, a.bairro, a.observacoes ";
$sql .= " from alunos a ";
$sql .= " order by a.nome ";

// Calculo a quantidade de registros
$resultado_total = $db->Execute($sql);
if ($resultado_total === false) die ("Não foi possível consultar a tabela alunos");
$ultimo = $resultado_total->RecordCount();

// Barra de navegacao superior
switch ($botao) {
	case "primeiro":
		$indice = 0;
		break;
	case "menos_10":
		$indice = $indice -10;
		if($indice < 0)
		$indice = $ultimo > 0 ? $ultimo - 1 : 0;
		break;
	case "retroceder":
		$indice--;
		if($indice <= 0)
		$indice = $ultimo > 0 ? $ultimo - 1 : 0;
		break;
	case "avancar":
		$indice++;
		if($indice >= $ultimo)
		$indice = 0;
		break;
	case "mais_10":
		$indice = $indice + 10;
		if($indice >= $ultimo)
		$indice = 0;
		break;
	case "ultimo":
		$indice = $ultimo > 0 ? $ultimo - 1 : 0;
		break;
	default:
		if ($indice < 0) $indice = 0;
		break;
}

if (!empty($debug)) echo $_SERVER['HTTP_REFERER'] ?? '';

// Se foi chamado desde outro lugar atraves de um aluno_id calculo o inicio da contagem
if (!empty($aluno_id)) {
	$resultado_sql_lugar = $db->Execute($sql);
	if ($resultado_sql_lugar === false) die ("Não foi possível consultar a tabela alunos");
	$j = 0;
	while (!$resultado_sql_lugar->EOF) {
		$lugar_aluno = $resultado_sql_lugar->fields['id'];
		if ($lugar_aluno == $aluno_id) {
			$indice = $j;
			break;
		}
		$resultado_sql_lugar->MoveNext();
		$j++;
	}
}

$nome = '';
$codigo_telefone = '';
$telefone = '';
$codigo_celular = '';
$celular = '';
$email = '';
$cpf = '';
$identidade = '';
$orgao = '';
$nascimento = '';
$endereco = '';
$cep = '';
$bairro = '';
$municipio = '';
$observacoes = '';
$periodo_intro = '';
$tempo_cursado = '';
$historico_estagio = array();
$instituicao_id = 0;
$supervisor_id = 0;

$resultado = $db->SelectLimit($sql,1,$indice);

if ($resultado === false) die ("Nao foi possivel consultar a tabela alunos");
while (!$resultado->EOF) {
	$aluno_id        = $resultado->fields['id'];
	$registro        = $resultado->fields['registro'];
	$nome            = $resultado->fields['nome'];
	$ingresso        = $resultado->fields['ingresso'];
	$codigo_telefone = $resultado->fields['codigo_telefone'];
	$telefone        = $resultado->fields['telefone'];
	$codigo_celular  = $resultado->fields['codigo_celular'];
	$celular         = $resultado->fields['celular'];
	$email           = strtolower($resultado->fields['email'] ?? '');
	$cpf             = $resultado->fields['cpf'];
	$identidade      = $resultado->fields['identidade'];
	$orgao           = $resultado->fields['orgao'];
	$nascimento      = $resultado->fields['nascimento'];
	$endereco        = $resultado->fields['endereco'];
	$cep             = $resultado->fields['cep'];
	$bairro          = $resultado->fields['bairro'];
	$municipio       = $resultado->fields['municipio'];
	$observacoes     = $resultado->fields['observacoes'];
	$periodo_intro   = $ingresso;
	$resultado->MoveNext();

	// Calculo o periodo atual
	if (!empty($periodo_atual) && !empty($ingresso)) {
		$tempo0 = explode("-",$ingresso);
		if (count($tempo0) >= 2) {
			$tempo_inicial = (int)$tempo0[0];
			$periodo_inicial = (int)$tempo0[1];
			$tempo1 = explode("-",$periodo_atual);
			if (count($tempo1) >= 2) {
				$tempo_final = (int)$tempo1[0];
				$periodo_final = (int)$tempo1[1];
				$tempo_cursado = ($tempo_final - $tempo_inicial);

				if ($periodo_inicial < $periodo_final) {
					$tempo_cursado = ($tempo_cursado * 2) + 2;
				} elseif ($periodo_inicial > $periodo_final) {
					$tempo_cursado = ($tempo_cursado * 2);
				} elseif ($periodo_inicial === $periodo_final) {
					$tempo_cursado = ($tempo_cursado * 2) + 1;
				}
			}
		}
	}

	// Pego a informacao sobre os estagios cursados
	$sql_estagiario = "select id, tc, nivel, periodo, nota, ch, instituicao_id, supervisor_id, professor_id from estagiarios where aluno_id = '" . addslashes($aluno_id) . "' order by periodo";
	$resultado_estagiario = $db->Execute($sql_estagiario);
	if ($resultado_estagiario === false) die ("Nao foi possivel consultar a tabela estagiarios");
	$i = 0;
	while (!$resultado_estagiario->EOF) {
		$estagiario_id      = $resultado_estagiario->fields['id'];
		$tc                 = $resultado_estagiario->fields['tc'];
		$nivel              = $resultado_estagiario->fields['nivel'];
		$estagiario_periodo = $resultado_estagiario->fields['periodo'];
		$nota               = $resultado_estagiario->fields['nota'];
		$ch                 = $resultado_estagiario->fields['ch'];
		$instituicao_id     = $resultado_estagiario->fields['instituicao_id'];
		$supervisor_id      = $resultado_estagiario->fields['supervisor_id'];
		$professor_id       = $resultado_estagiario->fields['professor_id'];

		$resultado_estagiario->MoveNext();

		if (empty($instituicao_id)) {
			$instituicao_id = "0";
			$instituicao = "Sem dados";
		} else {
			$sql_estagio = "select id, instituicao from instituicoes where id = " . (int)$instituicao_id;
			$resposta_estagio = $db->Execute($sql_estagio);
			$id          = $resposta_estagio ? $resposta_estagio->fields['id'] : 0;
			$instituicao = $resposta_estagio ? $resposta_estagio->fields['instituicao'] : "Sem dados";
		}

		$supervisor_nome = "Sem dados";
		if (!empty($supervisor_id)) {
			$sql_supervisor  = "select id, cress, nome, email ";
			$sql_supervisor .= "from supervisores ";
			$sql_supervisor .= "where supervisores.id = " . (int)$supervisor_id;
			$resultado_supervisor = $db->Execute($sql_supervisor);
			if ($resultado_supervisor && !$resultado_supervisor->EOF) {
				$supervisor_nome  = $resultado_supervisor->fields['nome'];
				$supervisor_cress = $resultado_supervisor->fields['cress'];
				$supervisor_email = $resultado_supervisor->fields['email'];
			}
		} else {
			$supervisor_id = "0";
		}

		// Professor
		$professor_nome = "Sem dados";
		if (!empty($professor_id)) {
			$sql_professor = "select nome from professores where id = " . (int)$professor_id;
			$resultado_professor = $db->Execute($sql_professor);
			if ($resultado_professor && !$resultado_professor->EOF) {
				$professor_nome = $resultado_professor->fields['nome'];
			}
		}
		 
		$historico_estagio[$i]['nivel']          = $nivel;
		$historico_estagio[$i]['estagiario_id']  = $estagiario_id;
		$historico_estagio[$i]['tc']             = $tc;
		$historico_estagio[$i]['turno']          = '';
		$historico_estagio[$i]['periodo']        = $estagiario_periodo;
		$historico_estagio[$i]['nota']           = $nota;
		$historico_estagio[$i]['ch']             = $ch;
		$historico_estagio[$i]['instituicao_id'] = $instituicao_id;
		$historico_estagio[$i]['instituicao']    = $instituicao;
		$historico_estagio[$i]['supervisor_id']  = $supervisor_id;
		$historico_estagio[$i]['supervisor']     = $supervisor_nome;
		$historico_estagio[$i]['professor_id']   = $professor_id;
		$historico_estagio[$i]['professor']      = $professor_nome;

		$i++;
	}
}

// Pego a informacao sobre as turma de alunos
$periodos = array();
$sqlturma = "select id, periodo from estagiarios group by periodo";
$res_turma = $db->Execute($sqlturma);
if ($res_turma === false) die ("Não foi possível consultar a tabela estagiarios");
while (!$res_turma->EOF) {
	$periodos[] = $res_turma->fields['periodo'];
	$res_turma->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("logado", $isAdmin);
$smarty->assign("isAdmin", $isAdmin);
$smarty->assign("periodo", $periodo);
$smarty->assign("origem", $origem);
$smarty->assign("indice", $indice);
$smarty->assign("aluno_id", $aluno_id);
$smarty->assign("instituicao_id", $instituicao_id);
$smarty->assign("supervisor_id", $supervisor_id);
$smarty->assign("registro", $registro);
$smarty->assign("nome", $nome);
$smarty->assign("codigo_telefone", $codigo_telefone);
$smarty->assign("telefone", $telefone);
$smarty->assign("codigo_celular", $codigo_celular);
$smarty->assign("celular", $celular);
$smarty->assign("email", $email);
$smarty->assign("cpf", $cpf);
$smarty->assign("identidade", $identidade);
$smarty->assign("orgao", $orgao);
$smarty->assign("nascimento", $nascimento);
$smarty->assign("endereco", $endereco);
$smarty->assign("cep", $cep);
$smarty->assign("bairro", $bairro);
$smarty->assign("municipio", $municipio);
$smarty->assign("observacoes", $observacoes);
$smarty->assign("periodo_intro", $periodo_intro);
$smarty->assign("tempo_cursado", $tempo_cursado);
$smarty->assign("historico_estagio", $historico_estagio);
$smarty->assign("periodos", $periodos);

$smarty->display("alunos-exibir_ver_cada.tpl");

exit;

?>
