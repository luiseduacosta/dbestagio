<?php

include_once("../autentica.inc");

// Variavel com o nome do aluno para saber se um registro foi inserido na selecao de estagio
$insere = isset($_GET['insere']) ? $_GET['insere'] : '';
$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : '';
if (empty($ordem)) {
	$ordem = "data_inscricao desc";
}

// Pego os estagios do PERIODO_ATUAL
$sql  = "select id, instituicao_id, instituicao, convenio, vagas, beneficios, final_de_semana, ";
$sql .= "carga_horaria, requisitos, ";
$sql .= "horario, data_selecao, horario_selecao, data_inscricao, ";
$sql .= "local_selecao, forma_selecao, contato, outras ";
$sql .= "from mural_estagios ";
$sql .= "where periodo = '" . PERIODO_ATUAL . "' ";
$sql .= "order by $ordem";

// echo $sql . "<br>";

$resultado = $db->Execute($sql);

if ($resultado === false) die("Não foi possível consultar a tabela mural_estagios");
$i = 0;
$totalVagas = 0;
$instituicao = array();
while (!$resultado->EOF) {
		$instituicao[$i]['mural_estagio_id'] = $resultado->fields['id'];
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

		// Passo do formato aaaa/mm/dd para dd/mm/aaaa
		if ($resultado->fields['data_selecao'] == 0) {
			$data_selecao = "00-00-0000";
		} else {
			$data_selecao = date("d-m-Y",strtotime($resultado->fields['data_selecao']));
		}
		$instituicao[$i]['data_selecao'] = $data_selecao;

		$instituicao[$i]['horario_selecao'] = $resultado->fields['horario_selecao'];
		
		// Passo do formato aaaa/mm/dd para dd/mm/aaaa		
		if ($resultado->fields['data_inscricao'] == 0) {
			$data_inscricao = "00-00-0000";
		} else {
			$data_inscricao = date("d-m-Y",strtotime($resultado->fields['data_inscricao']));
		}
		$instituicao[$i]['data_inscricao'] = $data_inscricao;
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

		$mural_estagio_id = $resultado->fields['id'];	
		$sql_alunos = "select count(registro) as alunos from inscricoes where muralestagio_id='$mural_estagio_id' and periodo='" . PERIODO_ATUAL . "'";
		// echo $sql_alunos . "<br>";
		$resultado_alunos = $db->Execute($sql_alunos);
		$instituicao[$i]['quantidade_alunos'] = $resultado_alunos ? $resultado_alunos->fields['alunos'] : 0;
		
		$resultado->MoveNext();
		$i++;
}

// Calculo o total de alunos que procuram estagio
$sql = "SELECT DISTINCT registro FROM inscricoes WHERE periodo='". PERIODO_ATUAL . "'";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela inscricoes");
$total = $resultado->RecordCount();
$conhecidos = 0;
$estagio_um = 0;
$i = 0;
while (!$resultado->EOF) {
	$registro = $resultado->fields['registro'];

	$sqlVelho = "select registro from estagiarios where registro='$registro' group by registro";
	$resultadoVelho = $db->Execute($sqlVelho);
	$numero_aluno = $resultadoVelho ? $resultadoVelho->fields['registro'] : null;

	if (!empty($numero_aluno)) {

		$conhecidos++;

		$sql_velho = "select registro from estagiarios where registro = '$numero_aluno' and periodo = '" .PERIODO_ATUAL . "' and nivel = 1 group by registro";
		$res_velho = $db->Execute($sql_velho);
		$dre_aluno = $res_velho ? $res_velho->fields['registro'] : null;
		if (!empty($dre_aluno)) {
			$estagio_um++;
		}
	}
	$i++;
	$resultado->MoveNext();
}

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

?>
