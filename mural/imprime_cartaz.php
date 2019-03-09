<?php

include_once("../setup.php");

$id_instituicao = $_REQUEST['id_instituicao'];

define("FPDF_FONTPATH","/usr/local/htdocs/html/fpdf151/font/");
define("FPDF","/usr/local/htdocs/html/fpdf151/");

require(FPDF."fpdf.php");

$pdf=new FPDF();
$pdf->Open();
$pdf->AddPage();
// $pdf->SetMargins(30,20,30);
$pdf->SetFont("Arial","","16");

$pdf->Image("../imprimir/minerva.jpg",25,30,20,20,jpg);
$pdf->Image("../imprimir/LogoESS.jpg",170,30,20,20,jpg);
$pdf->Ln(5);
$cabecalho1 = $pdf->GetStringWidth("UNIVERSIDADE FEDERAL DO RIO DE JANEIRO");
$pdf->SetX((210-$cabecalho1)/2);
$pdf->Cell($cabecalho1,9,"UNIVERSIDADE FEDERAL DO RIO DE JANEIRO",0,1,'C',0);
$pdf->Ln(2);

$cabecalho2 = $pdf->GetStringWidth("Escola de Serviço Social");
$pdf->SetX((210-$cabecalho2)/2);
$pdf->Cell($cabecalho2,9,"Escola de Serviço Social",0,1,'C',0);
$pdf->Ln(2);

$cabecalho3 = $pdf->GetStringWidth("Coordenação de Estágio e Extensão");
$pdf->SetX((210-$cabecalho3)/2);
$pdf->Cell($cabecalho3,9,"Coordenação de Estágio e Extensão",0,1,'C',0);
$pdf->Ln(2);

$cabecalho4 = $pdf->GetStringWidth("Seleção para estágio ");
$pdf->SetX((210-$cabecalho4)/2);
$pdf->SetFont("Arial","IB","24");
$pdf->Cell($cabecalho4,9,"Seleção para estágio",0,1,'C',0);
$pdf->Ln(2);

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

if($resultado === false) die ("Não foi possível consultar a tabela mural_estagio");
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
		$data_selecao = date("d-m-Y",strtotime($resultado->fields['dataSelecao']));

		$horarioSelecao = $resultado->fields['horarioSelecao'];

		$data_inscricao = date("d-m-Y",strtotime($resultado->fields['dataInscricao']));

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

$pdf->Ln(10);
$pdf->SetX(20);
// $pdf->Cell(0,0,"Institui��o: ");
// $pdf->SetX(70);
$pdf->SetFont("Arial","B","18");
$pdf->SetLineWidth(0,5);
$pdf->MultiCell(160,8,$instituicao,1);

$pdf->SetFont("Arial","","14");
$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Vagas:");
$pdf->SetX(70);
$pdf->Cell(0,0,$vagas);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Benefícios:");
$pdf->SetX(70);
$pdf->Cell(0,0,$beneficios);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Final de semana?:");
$pdf->SetX(70);
$pdf->Cell(0,0,$final_de_semana);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Carga horária:");
$pdf->SetX(70);
$pdf->Cell(0,0,$cargaHoraria);

if(!empty($requisitos)) {
    $pdf->Ln(10);
    $pdf->SetX(20);
    $pdf->Cell(0,0,"Requisitos:");
    // $pdf->Ln(5);
    $pdf->SetLineWidth(0,5);
    $pdf->SetFont("Arial","","14");
    $pdf->SetX(70);
    $pdf->MultiCell(110,5,$requisitos,1);
}

$pdf->SetFont("Arial","","14");
$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Área:");
$pdf->SetX(70);
$pdf->Cell(0,0,$area);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Professor:");
$pdf->SetX(70);
$pdf->Cell(0,0,$professor);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Horário da OTP:");
$pdf->SetX(70);
$pdf->Cell(0,0,$horario);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Inscrições até o dia:");
$pdf->SetX(70);
$pdf->Cell(0,0,$data_inscricao);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Data da seleção:");
$pdf->SetX(70);
$pdf->Cell(0,0,$data_selecao);
$pdf->SetX(100);
$pdf->Cell(0,0,"horário:");
$pdf->SetX(120);
$pdf->Cell(0,0,$horarioSelecao);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Local da seleção:");
$pdf->SetX(70);
$pdf->MultiCell(100,5,$localSelecao);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Forma de seleção:");
$pdf->SetX(70);
$pdf->Cell(0,0,$formaSelecao);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Contatos:");
$pdf->SetX(70);
$pdf->Cell(0,0,$contato);

// echo strlen($outras);
if(strlen(trim($outras)) > 250) {
    $pdf->AddPage();
}

$pdf->SetFont("Arial","","14");
$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Outras informações:");
$pdf->Ln(5);
$pdf->SetX(20);
$pdf->SetLineWidth(0,5);
$pdf->MultiCell(160,5,$outras,1);

$pdf->Ln(8);
$pdf->SetX(55);
$pdf->SetFont("Arial","U","16");
$pdf->Cell(0,0,"http://www.ess.ufrj.br/estagio");

$pdf->Output();

?>