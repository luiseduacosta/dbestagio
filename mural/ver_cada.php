<?php

include_once("../setup.php");

$instituicao_id = $_REQUEST['instituicao_id'];
$indice = $_REQUEST['indice'];
$submit = $_REQUEST['submit'];
$botao  = $_REQUEST['botao'];

/*
echo "instituicao_id: " . $instituicao_id . "<br/>";
echo " Indice: " . $indice  . "<br/>";
echo " Submit: " . $submit  . "<br/>";
echo " Botao: " . $botao  . "<br/>";
*/

// Calculo a quantidade de registros
$sql = "select id from mural_estagios where periodo = '" . PERIODO_ATUAL . "'";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela mural_estagios");
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
if (!empty($instituicao_id)) {
	// Pode ter mais de uma instituicao por periodo por isso tem que ordenar por instituicao e id
	$sql_instituicao  = "select id, instituicao from mural_estagios ";
	$sql_instituicao .= " where periodo = '" . PERIODO_ATUAL . "' ";
	$sql_instituicao .= " order by instituicao";
	// echo $sql_instituicao . "<br>";
	$res_instituicao = $db->Execute($sql_instituicao);
	if ($res_instituicao === false) die ("Não foi possível consultar a tabela mural_estagios");
	$lugar = 0;
	while (!$res_instituicao->EOF)	{
		$num_instituicao = $res_instituicao->fields['id'];
		if ($num_instituicao === $instituicao_id) {
			// echo $indice . " " . $num_instituicao . " " . $instituicao_id . "<br>";
			$indice = $lugar;
			break;
		}
		// echo "indice -> " . $indice . " lugar -> " . $lugar .  "<br />";
		$lugar++;
		$res_instituicao->MoveNext();
	}
}

$sql_estagio  = "select id, instituicao_id, instituicao, convenio, vagas, beneficios, final_de_semana, ";
$sql_estagio .= "carga_horaria, requisitos, ";
$sql_estagio .= "horario, data_selecao, horario_selecao, data_inscricao, ";
$sql_estagio .= "local_selecao, forma_selecao, contato, email, outras ";
$sql_estagio .= "from mural_estagios ";
$sql_estagio .= "where periodo = '" . PERIODO_ATUAL . "' ";
$sql_estagio .= "order by instituicao";

$resultado = $db->SelectLimit($sql_estagio,1,$indice);

if ($resultado === false) die ("3 Não foi possível consultar a tabela mural_estagios");
$i = 0;
while (!$resultado->EOF) {
		$instituicao[$i]['muralestagio_id'] = $resultado->fields['id'];
		$instituicao[$i]['instituicao'] = $resultado->fields['instituicao'];
		$instituicao[$i]['convenio'] = $resultado->fields['convenio'];
		$instituicao[$i]['vagas'] = $resultado->fields['vagas'];
		$instituicao[$i]['beneficios'] = $resultado->fields['beneficios'];
		$muralestagio_id = $resultado->fields['id'];

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
		if ($horario === "D") {
			$horario = "Diurno";
		} elseif ($horario === "N") {
			$horario = "Noturno";
		} elseif ($horario === "A") {
			$horario = "Ambos";
		}
		$instituicao[$i]['horario'] = $horario;

		// Passo do formato aaaa/mm/dd para dd/mm/aaaa		
		$data_selecao = $resultado->fields['data_selecao'];
		// Transformo a data de aaaa-mm-dd para dd-mm-aaaa
		if ($data_selecao == 0) {
			$data_selecao = "00-00-0000";
			// echo "Inscrição diretamente na instituição";
		} else {
			$data_selecao = date("Ymd",strtotime($data_selecao));
		}
		$instituicao[$i]['data_selecao'] = date("d-m-Y",strtotime($data_selecao));
		
		$instituicao[$i]['horario_selecao'] = $resultado->fields['horario_selecao'];

		// Passo do formato aaaa/mm/dd para dd/mm/aaaa
		$data_inscricao = $resultado->fields['data_inscricao'];
		if ($data_inscricao == 0) {
			$data_inscricao = "00-00-0000";
		} else {
			$data_inscricao = date("Ymd",strtotime($data_inscricao));
		}
		$instituicao[$i]['data_inscricao'] = date("d-m-Y",strtotime($data_inscricao));
		
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
		$instituicao[$i]['email'] = $resultado->fields['email'];

		$instituicao[$i]['outras'] = $resultado->fields['outras'];

		$resultado->MoveNext();
		$i++;
}

// echo "Indice " . $indice . "<br>";
$data_hoje = date("Ymd");
// echo "Data hoje: " . date("Ymd") . "<br>";

// echo "Usuário: " . $_COOKIE['usuario_nome'];
if ($_COOKIE['usuario_nome']) $sistema_autentica = 1;

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("muralestagio_id",$muralestagio_id);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("data_hoje",$data_hoje);
$smarty->assign("data_inscricao",$data_inscricao);
$smarty->assign("data_selecao",$data_selecao);
$smarty->assign("data_fax",$data_fax);
$smarty->assign("indice",$indice);
$smarty->display("../../mural/ver_cada.tpl");

exit;

?>
