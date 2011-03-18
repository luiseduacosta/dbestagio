<?php

include_once("../autoriza.inc");
include_once("../setup.php");

$id_instituicao = $_REQUEST['id_instituicao'];
$indice = $_REQUEST['indice'];
$submit = $_REQUEST['submit'];
$botao  = $_REQUEST['botao'];

/*
echo "id_instituicao: " . $id_instituicao . "<br/>";
echo " Indice: " . $indice  . "<br/>";
echo " Submit: " . $submit  . "<br/>";
echo " Botao: " . $botao  . "<br/>";
*/

// Calculo a quantidade de registros
$sql = "select id from mural_estagio where periodo = '" . PERIODO_ATUAL . "'";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela mural_estagio");
$num_linhas = $resultado->RecordCount();
// echo $num_linhas . "<br>";
$ultimo_registro = $num_linhas - 1;

switch($botao) 
{
    case "primeiro":
	$indice = 0;
	break;

    case "menos_1";
	$indice--;
	if($indice < 0)
	    $indice = $num_linhas -1;
	break;

    case "menos_10":
	$indice = $indice - 10;
	if($indice < 0)
	    $indice = $ultimo_registro - abs($indice);
	break;

    case "mais_1":
	$indice++;
	if($indice == $num_linhas)
	    $indice = 0;
	break;

    case "mais_10":
	$indice = $indice + 10;
	if($indice > $ultimo_registro)
	    $indice = $indice - $num_linhas;
	break;

    case "ultimo":
	$indice = $ultimo_registro;
	break;
}

// Quando nao tenho o indice, o calculo a partir do id_instituicao
if (!empty($id_instituicao)) {
	// Pode ter mais de uma instituicao por periodo por isso tem que ordenar por instituicao e id
	$sql_instituicao  = "select id, instituicao from mural_estagio ";
	$sql_instituicao .= " where periodo = '" . PERIODO_ATUAL . "' ";
	$sql_instituicao .= " order by instituicao, id";
	// echo $sql_instituicao . "<br>";
	$res_instituicao = $db->Execute($sql_instituicao);
	if ($res_instituicao === false) die ("Não foi possível consultar a tabela mural_estagio");
	$lugar = 0;
	while (!$res_instituicao->EOF)	{
		$num_instituicao = $res_instituicao->fields['id'];
		if ($num_instituicao === $id_instituicao) {
			// echo $indice . " " . $num_instituicao . " " . $id_instituicao . "<br>";
			$indice = $lugar;
			// break;
		}
		// echo "indice -> " . $indice . " lugar -> " . $lugar .  "<br />";
		$lugar++;
		$res_instituicao->MoveNext();
	}
}

$sql_estagio  = "select mural_estagio.id, id_estagio, instituicao, convenio, vagas, beneficios, final_de_semana, ";
$sql_estagio .= "cargaHoraria, requisitos, ";
$sql_estagio .= "id_area, area, id_professor, nome, horario, dataSelecao, horarioSelecao, dataInscricao, ";
$sql_estagio .= "localSelecao, formaSelecao, contato, mural_estagio.email, datafax, outras ";
$sql_estagio .= "from mural_estagio ";
$sql_estagio .= "left outer join areas_estagio on (mural_estagio.id_area = areas_estagio.id) ";
$sql_estagio .= "left outer join professores on (mural_estagio.id_professor = professores.id) ";
$sql_estagio .= "where mural_estagio.periodo = '" . PERIODO_ATUAL . "' ";
$sql_estagio .= "order by instituicao, id";

// echo $sql_estagio . "<br />";
// echo $indice . "<br />";

$resultado = $db->SelectLimit($sql_estagio,1,$indice);

if ($resultado === false) die ("3 Não foi possível consultar a tabela mural_estagio");
$i = 0;
while (!$resultado->EOF) {
		$instituicao[$i]['id_instituicao'] = $resultado->fields['id'];
		$instituicao[$i]['id_estagio'] = $resultado->fields['id_estagio'];
		$instituicao[$i]['instituicao'] = $resultado->fields['instituicao'];
		$instituicao[$i]['convenio'] = $resultado->fields['convenio'];
		$instituicao[$i]['vagas'] = $resultado->fields['vagas'];
		$instituicao[$i]['beneficios'] = $resultado->fields['beneficios'];
		$id_instituicao = $resultado->fields['id'];

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
		if ($horario === "D") {
			$horario = "Diurno";
		} elseif ($horario === "N") {
			$horario = "Noturno";
		} elseif ($horario === "A") {
			$horario = "Ambos";
		}
		$instituicao[$i]['horario'] = $horario;

		// Passo do formato aaaa/mm/dd para dd/mm/aaaa		
		// echo $dataSelecao = date('Ymd',strtotime($resultado->fields['dataSelecao']));
		$dataSelecao = $resultado->fields['dataSelecao'];
		// Transformo a data de aaaa-mm-dd para dd-mm-aaaa
		if ($dataSelecao == 0) {
			$data_selecao = "00-00-0000";
			// echo "Inscrição diretamente na instituição";
		} else {
			$data_selecao = date("Ymd",strtotime($dataSelecao));
		}
		// $instituicao[$i]['dataSelecao'] = date('d-m-Y',strtotime($resultado->fields['dataSelecao']));
		$instituicao[$i]['dataSelecao'] = date("d-m-Y",strtotime($dataSelecao));
		
		$instituicao[$i]['horarioSelecao'] = $resultado->fields['horarioSelecao'];

		// Passo do formato aaaa/mm/dd para dd/mm/aaaa
		$dataInscricao = $resultado->fields['dataInscricao'];
		if ($dataInscricao == 0) {
			$data_inscricao = "00-00-0000";
		} else {
			$data_inscricao = date("Ymd",strtotime($dataInscricao));
		}
		// $instituicao[$i]['dataInscricao'] = date("d-m-Y",strtotime($resultado->fields['dataInscricao']));		
		$instituicao[$i]['dataInscricao'] = date("d-m-Y",strtotime($dataInscricao));
		// echo "Data encerramento: " . $data_encerramento . "<br>";
		
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
		$instituicao[$i]['email'] = $resultado->fields['email'];
		
		if ($resultado->fields['datafax'] == 0)  {
			$data_fax = ""; // echo "Vazio<br>";
			$instituicao[$i]['datafax'] = "";
		} else {
			$data_fax = date("d-m-Y",strtotime($resultado->fields['datafax']));
			$instituicao[$i]['datafax'] = date("d-m-Y",strtotime($resultado->fields['datafax']));
		}

		// echo "data " . $data_fax . "<br>";
		
		$instituicao[$i]['outras'] = $resultado->fields['outras'];

		$resultado->MoveNext();
		$i++;
}

// echo "Indice " . $indice . "<br>";
$data_hoje = date("Ymd");
// echo "Data hoje: " . date("Ymd") . "<br>";

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("data_hoje",$data_hoje);
$smarty->assign("data_inscricao",$data_inscricao);
$smarty->assign("data_selecao",$data_selecao);
$smarty->assign("data_fax",$data_fax);
$smarty->assign("indice",$indice);
// $smarty->assign("opcao",$opcao);
$smarty->display("../../mural/ver_cada.tpl");

exit;

?>
