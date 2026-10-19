<?php

include_once("../setup.php");
// models.php carrega o Mural e liga a conexão $db aos models (sem isso
// Mural::cartazPorId lança "Conexão ADOdb não definida").
require_once(__DIR__ . "/../libphp/models.php");

// Id da oferta (mural_estagios): muralestagio_id é o nome canônico usado
// pelos links e formulários do módulo.
$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;
if ($muralestagio_id <= 0) die("Faltou passar o parâmetro muralestagio_id");

// FPDF_FONTPATH e FPDF já vêm definidos pelo setup.php.
require(FPDF."fpdf.php");
require_once(__DIR__ . "/../libphp/PDFHelper.php");

$pdf = new PDF_UTF8();
$pdf->AddPage();
// $pdf->SetMargins(30,20,30);
$pdf->SetFont("Arial","","16");

$pdf->Image("../imprimir/minerva.jpg",25,30,20,20,'jpg');
$pdf->Image("../imprimir/LogoESS.jpg",170,30,20,20,'jpg');
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

$dados = Mural::cartazPorId($muralestagio_id, PERIODO_ATUAL);
if ($dados === null) {
    die("Não há cartaz com id " . (int)$muralestagio_id . " no período atual");
}
extract($dados, EXTR_SKIP);

$pdf->Ln(10);
$pdf->SetFont("Arial","","14");
$pdf->SetX(20);
$pdf->Cell(0,0,"Instituição: ");
$pdf->SetX(70);
$pdf->SetLineWidth(0,5);
$pdf->MultiCell(110,8,strip_tags(html_entity_decode($instituicao)),1);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Vagas:");
$pdf->SetX(70);
$pdf->Cell(0,0,$vagas);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Benefícios:");
$pdf->SetX(70);
$pdf->Cell(0,0,strip_tags(html_entity_decode($beneficios)));

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Final de semana?:");
$pdf->SetX(70);
$pdf->Cell(0,0,$final_de_semana);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Carga horária:");
$pdf->SetX(70);
$pdf->Cell(0,0,strip_tags(html_entity_decode($carga_horaria)));

if(!empty($requisitos)) {
    $pdf->Ln(10);
    $pdf->SetX(20);
    $pdf->Cell(0,0,"Requisitos:");
    $pdf->SetLineWidth(0,5);
    $pdf->SetFont("Arial","","14");
    $pdf->SetX(70);
    $pdf->MultiCell(110,5,strip_tags(html_entity_decode($requisitos)),1);
}

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
$pdf->Cell(0,0,strip_tags(html_entity_decode($horario_selecao)));

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Local da seleção:");
$pdf->SetX(70);
if (!empty($local_selecao)) {
    $pdf->MultiCell(110,5,strip_tags(html_entity_decode($local_selecao)),1);
}

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Forma de seleção:");
$pdf->SetX(70);
$pdf->Cell(0,0,$forma_selecao);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Contatos:");
$pdf->SetX(70);
$pdf->MultiCell(110,5,strip_tags(html_entity_decode($contato)),1);

$pdf->SetFont("Arial","","14");
$texto_outras = strip_tags(html_entity_decode($outras));
$linhas_estimadas = max(1, ceil($pdf->GetStringWidth($texto_outras) / 160));
$altura_necessaria = $linhas_estimadas * 5 + 20;
$limite_pagina = $pdf->GetPageHeight() - 10;
if ($pdf->GetY() + $altura_necessaria > $limite_pagina) {
    $pdf->AddPage();
}

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Outras informações:");
$pdf->Ln(10);
$pdf->SetX(20);
$pdf->SetLineWidth(0,5);
$pdf->MultiCell(160,5,strip_tags(html_entity_decode($outras)),1);

$pdf->Ln(10);
$pdf->SetX(55);
$pdf->SetFont("Arial","U","14");
$pdf->Cell(0,0,"http://www.ess.ufrj.br");

$pdf->Output();

?>
