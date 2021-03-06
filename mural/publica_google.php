<?php

require_once("../setup.php");

$id_instituicao = $_REQUEST['id_instituicao'];

$sql_estagio  = "select mural_estagio.id, instituicao, convenio, vagas, beneficios, final_de_semana, ";
$sql_estagio .= "cargaHoraria, requisitos, ";
$sql_estagio .= "id_area, area, id_professor, nome, horario, dataSelecao, horarioSelecao, dataInscricao, ";
$sql_estagio .= "localSelecao, formaSelecao, contato, outras ";
$sql_estagio .= "from mural_estagio ";
$sql_estagio .= "left outer join areas_estagio on (mural_estagio.id_area = areas_estagio.id) ";
$sql_estagio .= "left outer join professores on (mural_estagio.id_professor = professores.id) ";
$sql_estagio .= "where mural_estagio.periodo = '" . PERIODO_ATUAL . "' ";
$sql_estagio .= "and mural_estagio.id = $id_instituicao";

// echo $sql_estagio . "<br />";
// echo $indice . "<br />";

$resultado = $db->Execute($sql_estagio);

if ($resultado === false) die ("Não foi possível consultar a tabela mural_estagio");
$i = 0;
while (!$resultado->EOF) {
		$id_instituicao = $resultado->fields['id'];
		$instituicao = $resultado->fields['instituicao'];
		$convenio = $resultado->fields['convenio'];
		$vagas = $resultado->fields['vagas'];
		$beneficios = $resultado->fields['beneficios'];

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
		$final_de_semana = $final_de_semana;

		$cargaHoraria = $resultado->fields['cargaHoraria'];
		$requisitos = $resultado->fields['requisitos'];
		$id_area = $resultado->fields['id_area'];
		$area = $resultado->fields['area'];
		$id_professor = $resultado->fields['id_professor'];
		$professor = $resultado->fields['nome'];

		$horario  = $resultado->fields['horario'];
		if ($horario === "D") {
			$horario = "Diurno";
		} elseif ($horario === "N") {
			$horario = "Noturno";
		} elseif ($horario === "A") {
			$horario = "Ambos";
		}
		$horario = $horario;

		// Passo do formato aaaa/mm/dd para dd/mm/aaaa
		$dataSelecao = $resultado->fields['dataSelecao'];
		$dataCorrigida = explode("-",$dataSelecao);
		$dataSQL = $dataCorrigida[2] . "-" . $dataCorrigida[1] . "-" . $dataCorrigida[0];
		$dataSelecao = $dataSQL;
		$data_selecao = $dataCorrigida[0] . "-" . $dataCorrigida[1] . "-" . $dataCorrigida[2];

		$horarioSelecao = $resultado->fields['horarioSelecao'];

		$dataInscricaoSelecao = $resultado->fields['dataInscricao'];
		$dataInscricaoCorrigida = explode("-",$dataInscricaoSelecao);
		$dataInscricaoSQL = $dataInscricaoCorrigida[2] . "-" . $dataInscricaoCorrigida[1] . "-" . $dataInscricaoCorrigida[0];
		$dataInscricao = $dataInscricaoSQL;
		$data_encerramento = $dataInscricaoCorrigida[0] . "-" . $dataInscricaoCorrigida[1] . "-" . $dataInscricaoCorrigida[2];

		$localSelecao = $resultado->fields['localSelecao'];

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
		$formaSelecao = $formaSelecao;

		$contato = $resultado->fields['contato'];
		$outras = $resultado->fields['outras'];

		$resultado->MoveNext();
		$i++;
}

$headers  = "From: Coordenação de estágio <estagio@ess.ufrj.br> \r\n";
$headers .= "Replay-To: estagio@ess.ufrj.br \r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$to = "estagio_ess@googlegroups.com ";
// $to = "Coloquio-Brasil-Uruguai-de-Servico-Social-garchive-90715@googlegroups.com";

$assunto = $instituicao;
$mensage  = "Receba informações também através do http://twitter.com/estagio_ess \n\n";
$mensage .= "Instituição: $instituicao \n";
$mensage .= "Vagas: $vagas \n";
$mensage .= "Benefícios: $beneficios \n";
$mensage .= "Final de semana: $final_de_semana \n";
$mensage .= "Carga horária: $cargaHoraria \n";
$mensage .= "Requisitos: $requisitos \n";
$mensage .= "Área: $area \n";
$mensage .= "Professor: $professor \n";
$mensage .= "Horário da OTP: $horario \n";
$mensage .= "Inscrições até: $dataInscricao \n";
$mensage .= "Data da seleção: $dataSelecao horário: $horarioSelecao \n";
$mensage .= "Local da seleção: $localSelecao \n";
$mensage .= "Forma de seleção: $formaSelecao \n";
$mensage .= "Contatos: $contato \n";
$mensage .= "Outras informações: $outras \n";
$mensage .= "Inscrições: http://www.ess.ufrj.br/estagio";

// echo $headers . "<br>";
// echo $assunto . "<br>";
echo utf8_encode($mensage) . "<br>";

// mail ($to,$assunto,$mensage,$headers);

// Twitter
require('../libphp/twitterAPI.php');
$twitter_message = htmlentities($vagas . " vagas de estágio em: " . $instituicao);
// echo $twitter_message . "<br>";
if(strlen($twitter_message)<1){ 
	$error=1;
} else {
	 $twitter_status=postToTwitter("estagio_ess", "e\$tagi0", $twitter_message);
}

// echo "<meta HTTP-EQUIV='refresh' content='0,URL=http://groups.google.com.br/group/estagio_ess'>";

header("Location: http://groups.google.com/forum/#!forum/estagio_ess");

?>
