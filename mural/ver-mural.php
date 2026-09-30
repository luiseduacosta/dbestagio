<?php

include_once(__DIR__ . "/../autentica.inc");

// Variavel com o nome do aluno para saber se um registro foi inserido na selecao de estagio
$insere = isset($_GET['insere']) ? $_GET['insere'] : '';

// Ordenacao: lista fechada de colunas permitidas (protecao contra SQL injection)
$ordens = array(
	'instituicao'    => 'instituicao',
	'vagas'          => 'vagas',
	'beneficios'     => 'beneficios',
	'data_inscricao' => 'data_inscricao',
	'data_selecao'   => 'data_selecao'
);
$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : '';
$orderby = isset($ordens[$ordem]) ? $ordens[$ordem] : 'data_inscricao desc';

// Pego os estagios do PERIODO_ATUAL
// A quantidade de inscritos de cada estagio e calculada na propria consulta (elimina N+1)
$sql  = "select m.id as mural_estagio_id, m.instituicao_id, m.instituicao, m.convenio, m.vagas, ";
$sql .= "m.beneficios, m.final_de_semana, m.carga_horaria, m.requisitos, ";
$sql .= "m.horario, m.data_selecao, m.horario_selecao, m.data_inscricao, ";
$sql .= "m.local_selecao, m.forma_selecao, m.contato, m.outras, ";
$sql .= "(select count(i.registro) from inscricoes as i where i.muralestagio_id = m.id and i.periodo = ?) as quantidade_alunos ";
$sql .= "from mural_estagios as m ";
$sql .= "where m.periodo = ? ";
$sql .= "order by $orderby";

$resultado = $db->Execute($sql, array(PERIODO_ATUAL, PERIODO_ATUAL));
if ($resultado === false) die("Não foi possível consultar a tabela mural_estagios");

$i = 0;
$totalVagas = 0;
$instituicao = array();
while (!$resultado->EOF) {
		$instituicao[$i]['mural_estagio_id'] = $resultado->fields['mural_estagio_id'];
		$instituicao[$i]['instituicao_id'] = $resultado->fields['instituicao_id'];
		$instituicao[$i]['instituicao'] = $resultado->fields['instituicao'];
		$instituicao[$i]['convenio'] = $resultado->fields['convenio'];
		$instituicao[$i]['vagas'] = $resultado->fields['vagas'];
		$instituicao[$i]['beneficios'] = $resultado->fields['beneficios'];

		$totalVagas = $totalVagas + $resultado->fields['vagas'];

		$final_de_semana = $resultado->fields['final_de_semana'];
		switch($final_de_semana) {
				case 0;
				$final_de_semana = "Não";
				break;

				case 1;
				$final_de_semana = "Sim";
				break;

				case 2;
				$final_de_semana = "Parcialmente";
				break;
		}
		$instituicao[$i]['final_de_semana'] = $final_de_semana;
		$instituicao[$i]['carga_horaria'] = $resultado->fields['carga_horaria'];
		$instituicao[$i]['requisitos'] = $resultado->fields['requisitos'];

		$horario  = $resultado->fields['horario'];
		if ($horario === "D")
			$horario = "Diurno";
		elseif ($horario === "N")
			$horario = "Noturno";
		elseif ($horario === "A")
			$horario = "Ambos";

		$instituicao[$i]['horario'] = $horario;

		// Passo do formato aaaa-mm-dd para dd-mm-aaaa na exibicao e mantenho o
		// formato ISO em um campo separado, usado na ordenacao pelo DataTables
		$ts_selecao = empty($resultado->fields['data_selecao']) ? false : strtotime($resultado->fields['data_selecao']);
		if ($ts_selecao === false) {
			$instituicao[$i]['data_selecao'] = '';
			$instituicao[$i]['data_selecao_iso'] = '';
		} else {
			$instituicao[$i]['data_selecao'] = date("d-m-Y", $ts_selecao);
			$instituicao[$i]['data_selecao_iso'] = date("Y-m-d", $ts_selecao);
		}

		$instituicao[$i]['horario_selecao'] = $resultado->fields['horario_selecao'];

		$ts_inscricao = empty($resultado->fields['data_inscricao']) ? false : strtotime($resultado->fields['data_inscricao']);
		if ($ts_inscricao === false) {
			$instituicao[$i]['data_inscricao'] = '';
			$instituicao[$i]['data_inscricao_iso'] = '';
		} else {
			$instituicao[$i]['data_inscricao'] = date("d-m-Y", $ts_inscricao);
			$instituicao[$i]['data_inscricao_iso'] = date("Y-m-d", $ts_inscricao);
		}

		$instituicao[$i]['local_selecao'] = $resultado->fields['local_selecao'];
		$forma_selecao = $resultado->fields['forma_selecao'];
		switch($forma_selecao) {
				case 0;
				$forma_selecao = "Entrevista";
				break;

				case 1;
				$forma_selecao = "CR";
				break;

				case 2;
				$forma_selecao = "Prova";
				break;

				case 3;
				$forma_selecao = "Outras";
				break;
		}
		$instituicao[$i]['forma_selecao'] = $forma_selecao;

		$instituicao[$i]['contato'] = $resultado->fields['contato'];
		$instituicao[$i]['outras'] = $resultado->fields['outras'];

		$instituicao[$i]['quantidade_alunos'] = $resultado->fields['quantidade_alunos'];

		$resultado->MoveNext();
		$i++;
}

// Calculo o total de alunos que procuram estagio (consultas set-based, sem N+1)
$sql = "select count(distinct registro) as total from inscricoes where periodo = ?";
$res_total = $db->Execute($sql, array(PERIODO_ATUAL));
if ($res_total === false) die("Nao foi possivel consultar a tabela inscricoes");
$total = (int) $res_total->fields['total'];

// Alunos ja conhecidos: registro existe na tabela estagiarios
$sql  = "select count(distinct i.registro) as conhecidos from inscricoes as i ";
$sql .= "where i.periodo = ? ";
$sql .= "and exists (select 1 from estagiarios as e where e.registro = i.registro)";
$res_conhecidos = $db->Execute($sql, array(PERIODO_ATUAL));
if ($res_conhecidos === false) die("Nao foi possivel consultar a tabela estagiarios");
$conhecidos = (int) $res_conhecidos->fields['conhecidos'];

// Alunos conhecidos que ja fizeram estagio de nivel 1 no periodo atual
$sql  = "select count(distinct i.registro) as estagio_um from inscricoes as i ";
$sql .= "where i.periodo = ? ";
$sql .= "and exists (select 1 from estagiarios as e where e.registro = i.registro and e.periodo = ? and e.nivel = 1)";
$res_estagio_um = $db->Execute($sql, array(PERIODO_ATUAL, PERIODO_ATUAL));
if ($res_estagio_um === false) die("Nao foi possivel consultar a tabela estagiarios");
$estagio_um = (int) $res_estagio_um->fields['estagio_um'];

// Calculo os novos como diferencia entre o total e os ja conhecidos
$novos = ($total - $conhecidos);
$novo_novo = $novos + $estagio_um;
$conhecidos_conhecidos = $conhecidos - $estagio_um;

$smarty = new Smarty_estagio;

$smarty->assign("periodo_atual", PERIODO_ATUAL);
$smarty->assign("sistema_autentica", $sistema_autentica);
$smarty->assign("insere", $insere);
$smarty->assign("instituicao", $instituicao);
$smarty->assign("totalVagas", $totalVagas);
$smarty->assign("totalAlunos", $total);
$smarty->assign("alunos_novos", $novo_novo);
$smarty->assign("alunosVelhos", $conhecidos_conhecidos);
$smarty->display("../../mural/ver-mural.tpl");

$db->Close();

exit;

?>
