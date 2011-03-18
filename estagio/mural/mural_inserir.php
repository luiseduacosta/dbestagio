<?php

// include_once("mural-autentica.inc");

include_once("../setup.php");

$confirma   = isset($_POST['inserir']) ? $_POST['inserir'] : NULL;
$aviso      = isset($_GET['aviso']) ? $_GET['aviso'] : NULL;

$convenio   = isset($_POST['convenio']) ? $_POST['convenio'] : NULL;
$id_estagio = isset($_POST['id_estagio']) ? $_POST['id_estagio'] : NULL;
$vagas = $_POST['vagas'];
$beneficios = $_POST['beneficios'];
$final_de_semana = $_POST['final_de_semana'];
$cargaHoraria = $_POST['cargaHoraria'];
$requisitos = $_POST['requisitos'];
$id_area = $_POST['id_area'];
$id_professor = $_POST['id_professor'];
$horario = $_POST['horario'];
$diaInscricao = $_POST['diaInscricao'];
$mesInscricao = $_POST['mesInscricao'];
$anoInscricao = $_POST['anoInscricao'];
$dia = $_POST['dia'];
$mes = $_POST['mes'];
$ano = $_POST['ano'];
$horarioSelecao = $_POST['horarioSelecao'];
$localSelecao = $_POST['localSelecao'];
$formaSelecao = $_POST['formaSelecao'];
$contato = $_POST['contato'];
$outras = $_POST['outras'];
$periodo = $_POST['periodo_atual'];
$email = $_POST['email'];

// Para salvar tenho que utilizar o formato aaaa/mm/dd/
$dataSelecao = $ano . "-" . $mes . "-" . $dia;
$dataInscricao = $anoInscricao . "-" . $mesInscricao . "-" . $diaInscricao;

// echo "Instituicao " . $instituicao . "<br>";
// echo "Vagas " . $vagas  . "<br>";
// echo "Beneficios ". $beneficios  . "<br>";
// echo "Final de semana " . $final_de_semana  . "<br>";
// echo "Id area ". $id_area  . "<br>";
// echo "Id professor " . $id_professor  . "<br>";
// echo "Horario " . $horario  . "<br>";
// echo "Data selecao " . $dataSelecao  . "<br>";
// echo "Dia " . $dia . " Mes " . $mes . " Ano " . $ano . "<br>";
// echo "Local selecao " . $localSelecao  . "<br>";
// echo "Forma selecao " . $formaSelecao  . "<br>";
// echo "Contato " . $contato  . "<br>";
// echo "Outras " . $outras  . "<br>";

// die("Confirma");

if($confirma == "Confirma") {
	if($convenio === "1") {
	    $sql = "select instituicao from estagio where id=$id_estagio";
	    $resultado = $db->Execute($sql);
	    $instituicao = $resultado->fields['instituicao'];

	    $sql = "insert into mural_estagio(id_estagio," .
		"instituicao, " .
		"convenio, ".
		"vagas," .
		"beneficios," .
		"final_de_semana," .
		"cargaHoraria, " .
		"requisitos, " .
		"id_area," .
		"id_professor," .
		"horario," .
		"dataInscricao," .
		"dataSelecao," .
		"horarioSelecao, ".
		"localSelecao," .
		"formaSelecao," .
		"contato," .
		"outras," .
		"periodo, " .
		"email) " .
		"values('$id_estagio', ".
		    "'$instituicao', ".
		    "'$convenio', ".
		    "'$vagas', ".
		    "'$beneficios', ".
		    "'$final_de_semana', ".
		    "'$cargaHoraria', ".
		    "'$requisitos', ".
		    "'$id_area', ".
		    "'$id_professor', ".
		    "'$horario', ".
		    "'$dataInscricao', ".
		    "'$dataSelecao', ".
		    "'$horarioSelecao', ".
		    "'$localSelecao', ".
		    "'$formaSelecao', ".
		    "'$contato', ".
		    "'$outras', ".
		    "'$periodo', ".
		    "'$email')";

	        // echo $sql . "<br>";
	        $resultado = $db->Execute($sql);
	        if($resultado === false) die ("Não foi possível inserir o registro na tabela mural_estagio");
	        $confirma = "";
    	        $id_instituicao = $db->Insert_ID();
	        // echo $id_instituicao . "<br>";
	        // die("Ver cada");
		echo "<meta HTTP-EQUIV='refresh' content='0,URL=ver_cada.php?id_instituicao=$id_instituicao'>";
		// header("Location:ver_cada.php?id_instituicao=$id_instituicao");
		exit;
	} elseif($convenio === "0") {
		echo "<meta HTTP-EQUIV='refresh' content='0,URL=mural_inserir.php?aviso='Instituição NÃO conveniada!'>";		
		// header("Location:mural_inserir.php?aviso='Instituição NÃO conveniada!'");
		exit;
	}
}

$sql = "select id, area from areas_estagio order by area";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela areas_estagio");

$i = 0;
$id_areas[$i] = 0;
$areas[$i] = "Seleciona área";
$i++;
while(!$resultado->EOF) {
	  $id_areas[$i] = $resultado->fields["id"];
	  $areas[$i]    = $resultado->fields["area"];
	  $i++;
	  $resultado->MoveNext();
}

$sqlProfessores = "select id, nome from professores order by nome";
$resultadoProfessores = $db->Execute($sqlProfessores);
if($resultadoProfessores === false) die ("Não foi possível consultar a tabela professores");
$i = 0;
$id_professores[$i] = 0;
$professores[$i]    = "Seleciona professor";
$i++;
while(!$resultadoProfessores->EOF) {
	  $id_professores[$i] = $resultadoProfessores->fields["id"];
	  $professores[$i]    = $resultadoProfessores->fields["nome"];
	  $i++;
	  $resultadoProfessores->MoveNext();
}

$sql_instituicoes = "select id, instituicao from estagio order by instituicao";
// echo $sql_instituicoes . "<br>";
$resultado_instituicoes = $db->Execute($sql_instituicoes);
if($resultado_instituicoes === false) die ("Não foi possível consultar a tabela estagio");
$i = 1;
while(!$resultado_instituicoes->EOF) {
    $instituicoes[$i]['id'] = $resultado_instituicoes->fields['id'];
    $instituicoes[$i]['instituicao'] = $resultado_instituicoes->fields['instituicao'];
    $i++;
    $resultado_instituicoes->MoveNext();
}

$periodo_atual = PERIODO_ATUAL;
// echo "Periodo " . $periodo_atual;

$smarty = new Smarty_estagio;

$smarty->assign("aviso",$aviso);
$smarty->assign("periodo_atual",$periodo_atual);
$smarty->assign("id_professores",$id_professores);
$smarty->assign("professores",$professores);
$smarty->assign("id_areas",$id_areas);
$smarty->assign("areas",$areas);
$smarty->assign("instituicoes",$instituicoes);
$smarty->display("../../mural/mural_inserir.tpl");

exit;

?>