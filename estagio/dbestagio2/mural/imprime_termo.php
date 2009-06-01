<?php

define("FPDF_FONTPATH","/usr/local/htdocs/html/fpdf151/font/");
define("FPDF","/usr/local/htdocs/html/fpdf151/");

require(FPDF."fpdf.php");

$pdf=new FPDF();
$pdf->Open();
$pdf->AddPage();
// $pdf->SetMargins(30,20,30);
$pdf->SetFont("Arial","","16");

$pdf->Ln(5);
$cabecalho1 = $pdf->GetStringWidth("UNIVERSIDADE FEDERAL DO RIO DE JANEIRO");
$pdf->SetX((210-$cabecalho1)/2);
$pdf->Cell($cabecalho1,9,"UNIVERSIDADE FEDERAL DO RIO DE JANEIRO",0,1,'C',0);
$pdf->Ln(2);

$cabecalho2 = $pdf->GetStringWidth("Escola de Serviчo Social");
$pdf->SetX((210-$cabecalho2)/2);
$pdf->Cell($cabecalho2,9,"Escola de Serviчo Social",0,1,'C',0);
$pdf->Ln(2);

$cabecalho3 = $pdf->GetStringWidth("Coordenaчуo de Estсgio e Extensуo");
$pdf->SetX((210-$cabecalho3)/2);
$pdf->Cell($cabecalho3,9,"Coordenaчуo de Estсgio e Extensуo",0,1,'C',0);
$pdf->Ln(2);

$pdf->SetFont("Arial","","14");
$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Aluno: ");
$pdf->SetX(70);
$pdf->SetLineWidth(0,5);
$pdf->Cell(0,0,$nome);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Instituicao:");
$pdf->SetX(70);
$pdf->Cell(0,0,$instituicao);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Supervisor:");
$pdf->SetX(70);
$pdf->Cell(0,0,$supervisor);

$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Nivel:");
$pdf->SetX(70);
if($nivel == 1) $nivel = "I";
if($nivel == 2) $nivel = "II";
if($nivel == 3) $nivel = "III";
if($nivel == 4) $nivel = "IV";

$pdf->Cell(0,0,$nivel);

$pdf->Output();

?>