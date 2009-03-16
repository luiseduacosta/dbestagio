<?php
/*
 * Created on 13/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once('/usr/local/htdocs/html/tcpdf/config/lang/eng.php');
require_once('/usr/local/htdocs/html/tcpdf/tcpdf.php'); 

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 011');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

//set some language-dependent strings
$pdf->setLanguageArray($l);

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

include("../setup.php");

$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : NULL;
$prof_id = isset($_REQUEST['id_professor']) ? $_REQUEST['id_professor'] : NULL;

// echo $periodo . " " . $prof_id . "<br>";

$sql  = "select alunos.registro, alunos.nome as aluno, nivel, id_professor, instituicao, supervisores.nome as supervisor, periodo from estagiarios ";
$sql .= " join alunos on estagiarios.registro = alunos.registro ";
$sql .= " join estagio on estagiarios.id_instituicao = estagio.id ";
$sql .= " join supervisores on estagiarios.id_supervisor = supervisores.id ";
$sql .=	" where periodo = '$periodo' "; 
$sql .=	" and id_professor = '$prof_id' ";

// echo $sql . "<br>";

$res = $db->Execute($sql);

$pdf->SetXY(20,20);

while (!$res->EOF) {
	$registro = $res->fields['registro'];
	$aluno = $res->fields['aluno'];
	$supervisor = $res->fields['supervisor'];
	$id_prof = $res->fields['id_professor'];
	$instituicao = $res->fields['instituicao'];
	$periodo = $res->fields['periodo'];
	$nivel = $res->fields['nivel'];
	
	$pdf->Cell(5, 7, '',1);
	$pdf->SetX(20);
    $pdf->Cell(15, 7, $registro);
	$pdf->SetX(45);
    $pdf->Cell(60, 7, $aluno);
	$pdf->SetX(95);
    $pdf->Cell(3, 7, $nivel);
	$pdf->SetX(100);
    $pdf->MultiCell(80, 1, $instituicao, 1);
	$pdf->SetX(180);
    $pdf->MultiCell(60, 1, $supervisor);

	// $pdf->Ln(5);
	
	// echo $registro . ' ' , $aluno . ' ' . $id_prof , ' ' . $instituicao . ' ' . $supervisor . ' ' . $periodo . ' ' . $nivel . "<br>";
	$res->MoveNext();
}

//Close and output PDF document
$pdf->Output('example_004.pdf', 'I'); 

?>
