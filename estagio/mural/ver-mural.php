<?php
/*
 * Created on 12/06/2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../autoriza.inc");

include_once("../db.inc");
include_once("../setup.php");

// Variavel com o nome do aluno para saber se um registro foi inserido na selecao de estagio
$insere = $_GET['insere'];
$ordem = $_GET['ordem'];
if(empty($ordem)) {
		$ordem = "dataInscricao desc";
}

// Pego os estágios do PERIODO_ATUAL
$sql  = "select mural_estagio.id, id_estagio, instituicao, convenio, vagas, beneficios, final_de_semana, ";
$sql .= "cargaHoraria, requisitos, ";
$sql .= "id_area, area, id_professor, nome, horario, dataSelecao, horarioSelecao, dataInscricao, ";
$sql .= "localSelecao, formaSelecao, contato, datafax, outras ";
$sql .= "from mural_estagio ";
$sql .= "left outer join areas_estagio on mural_estagio.id_area = areas_estagio.id ";
$sql .= "left outer join professores on mural_estagio.id_professor = professores.id ";
$sql .= "where mural_estagio.periodo = '" . PERIODO_ATUAL . "' ";
$sql .= "order by $ordem";

// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela mural_estagio");
$i = 0;
while (!$resultado->EOF) {
		$instituicao[$i]['id_instituicao'] = $resultado->fields['id'];
		$instituicao[$i]['id_estagio'] = $resultado->fields['id_estagio'];
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

		$instituicao[$i]['cargaHoraria'] = $resultado->fields['cargaHoraria'];
		$instituicao[$i]['requisitos'] = $resultado->fields['requisitos'];
		$instituicao[$i]['id_area'] = $resultado->fields['id_area'];
		$instituicao[$i]['area'] = $resultado->fields['area'];
		$instituicao[$i]['id_professor'] = $resultado->fields['id_professor'];
		$instituicao[$i]['professor'] = $resultado->fields['nome'];

		$horario  = $resultado->fields['horario'];
		if ($horario === "D")
			$horario = "Diurno";
		elseif ($horario === "N")
			$horario = "Noturno";
		elseif ($horario === "A")
			$horario = "Ambos";

		$instituicao[$i]['horario'] = $horario;

		// Passo do formato aaaa/mm/dd para dd/mm/aaaa
		if ($resultado->fields['dataSelecao'] == 0) {
			$data_selecao = "00-00-0000";
		} else {
			$data_selecao = date("d-m-Y",strtotime($resultado->fields['dataSelecao']));
		}
		$instituicao[$i]['dataSelecao'] = $data_selecao;

		$instituicao[$i]['horarioSelecao'] = $resultado->fields['horarioSelecao'];
		
		// Passo do formato aaaa/mm/dd para dd/mm/aaaa		
		if ($resultado->fields['dataInscricao'] == 0) {
			$data_inscricao = "00-00-0000";
		} else {
			$data_inscricao = date("d-m-Y",strtotime($resultado->fields['dataInscricao']));
		}
		$instituicao[$i]['dataInscricao'] = $data_inscricao;

		$instituicao[$i]['localSelecao'] = $resultado->fields['localSelecao'];

		$formaSelecao = $resultado->fields['formaSelecao'];
		switch($formaSelecao) {
				case 0;
				$formaSelecao = "Entrevista";
				break;

				case 1;
				$formaSelecao = "CR";
				break;

				case 2;
				$formaSelecao = "Prova";
				break;

				case 3;
				$formaSelecao = "Outras";
				break;
		}
		$instituicao[$i]['formaSelecao'] = $formaSelecao;

		$instituicao[$i]['contato'] = $resultado->fields['contato'];
		$instituicao[$i]['outras'] = $resultado->fields['outras'];
		if ($resultado->fields['datafax'] == 0)  {
			// echo "Vazio<br>";
			$instituicao[$i]['datafax'] = "";
		} else {
			$instituicao[$i]['datafax'] = date("d-m-Y",strtotime($resultado->fields['datafax']));
		}

		$id_instituicao = $resultado->fields['id'];	
		$sql_alunos = "select count(id_aluno) as alunos from mural_inscricao where id_instituicao='$id_instituicao' and periodo='" . PERIODO_ATUAL . "'";
		// echo $sql_alunos . "<br>";
		$resultado_alunos = $db->Execute($sql_alunos);
		$instituicao[$i]['quantidade_alunos'] = $resultado_alunos->fields['alunos'];
		
		$resultado->MoveNext();
		$i++;
}

// Calculo o total de alunos que procuram estagio
$sql = "SELECT id_aluno FROM mural_inscricao WHERE periodo='". PERIODO_ATUAL . "' group by id_aluno";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possivel consultar a tabela alunosNovos");
$total = $resultado->RecordCount();
// echo "Total " . $total . "<br>";

$conhecidos = 0;
while (!$resultado->EOF) {
	$registro = $resultado->fields['id_aluno'];
	// Conto a quantidade de alunos que ja estao em estagio
	$sqlVelho = "select registro from estagiarios where registro='$registro' and nivel != '1' group by registro";
	// echo $sqlVelho . "<br>";
	$resultadoVelho = $db->Execute($sqlVelho);
	while (!$resultadoVelho->EOF) {
			$conhecidos++;
			$resultadoVelho->MoveNext();
	}
	$resultado->MoveNext();
}

$sql_estagio_um = "select count(*) as estagio_um from estagiarios where periodo ='" . PERIODO_ATUAL . "' and nivel = '1' group by registro";
// echo $sql_estagio_um . "<br>";
$res_estagio_um = $db->Execute($sql_estagio_um);
$estagio_um = $res_estagio_um->fields['estagio_um'];

// echo "Conhecidos " . $conhecido . "<br>";
// Calculo os novos como diferencia entre o total e os ja conhecidos
$novos = ($total - $conhecidos) + $estagio_um;
// echo "Novos " . $novos . "<br>";

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("mural_autentica",$mural_autentica);
$smarty->assign("insere",$insere);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("totalVagas",$totalVagas);
$smarty->assign("totalAlunos",$total);
$smarty->assign("alunosNovos",$novos);
$smarty->assign("alunosVelhos",$conhecidos);
$smarty->display("ver-mural.tpl");
// $smarty->display("listaOfertas.tpl");

?>
