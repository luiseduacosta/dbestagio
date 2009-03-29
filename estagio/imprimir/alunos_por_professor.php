<?php
/*
 * Created on 13/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once('../libphp/tcpdf/config/lang/eng.php');
require_once('../libphp/tcpdf/tcpdf.php'); 

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Coordenacao de Estagio');
$pdf->SetTitle('Pauta de alunos estagiarios');
$pdf->SetSubject('Alunos estagiarios');
$pdf->SetKeywords('Estagio, Servico Social');

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
$professor = isset($_REQUEST['professor']) ? $_REQUEST['professor'] : NULL;
$id_area = isset($_REQUEST['id_area']) ? $_REQUEST['id_area'] : NULL;

// echo $periodo . " " . $prof_id . "<br>";

$sql  = "select alunos.registro, alunos.nome as aluno, nivel, id_professor, instituicao, supervisores.nome as supervisor, periodo from estagiarios ";
$sql .= " join alunos on estagiarios.registro = alunos.registro ";
$sql .= " join estagio on estagiarios.id_instituicao = estagio.id ";
$sql .= " left join supervisores on estagiarios.id_supervisor = supervisores.id ";
$sql .=	" where periodo = '$periodo' "; 
$sql .= " and id_area = $id_area ";
$sql .=	" and id_professor = '$prof_id' ";
$sql .= " order by alunos.nome ";

// echo $sql . "<br>";

$res = $db->Execute($sql);

$pdf->SetXY(15,25);

$titulo = 'Alunos estagiarios periodo ' . $periodo . ' do professor ' . $professor;

$pdf->Cell(0, 10, $titulo, 0, 0, 'C');
$pdf->Ln();

$pdf->Cell(10, 15, 'X',1, 0, 'C');
$pdf->Cell(25, 15, 'DRE', 1, 0, 'C');
$pdf->Cell(60, 15, 'Aluno', 1, 0, 'C');
$pdf->Cell(10, 15, 'Nivel', 1, 0, 'C');
$pdf->Cell(100, 15, 'Instituicao', 1, 0, 'C');
$pdf->Cell(60, 15, 'Supervisor', 1, 0, 'C');
$pdf->Ln(15);

while (!$res->EOF) {
	$registro = $res->fields['registro'];
	$aluno = $res->fields['aluno'];
	$supervisor = $res->fields['supervisor'];
	$id_prof = $res->fields['id_professor'];
	$instituicao = $res->fields['instituicao'];
	$periodo = $res->fields['periodo'];
	$nivel = $res->fields['nivel'];
	
	$pdf->Cell(10, 10, '',1);
	// $pdf->SetX(20);
    $pdf->Cell(25, 10, $registro, 1, 0, 'C');
	// $pdf->SetX(45);
    $pdf->Cell(60, 10, substr($aluno,0,27), 1);
	// $pdf->SetX(95);
    $pdf->Cell(10, 10, $nivel, 1, 0, 'C');
	// $pdf->SetX(100);
    $pdf->Cell(100, 10, substr($instituicao,0,37), 1);
	// $pdf->SetX(180);
    $pdf->Cell(60, 10, substr($supervisor, 0, 27), 1);

	$pdf->Ln(10);
	
	// echo $registro . ' ' , $aluno . ' ' . $id_prof , ' ' . $instituicao . ' ' . $supervisor . ' ' . $periodo . ' ' . $nivel . "<br>";
	$res->MoveNext();
}

$pdf->Ln(10);
$pdf->Cell(0, 10, 'Acrescentar aqui novos alunos para incluir na pauta',0, 0, 'C');
$pdf->Ln(10);

$pdf->Cell(10, 15, 'X',1, 0, 'C');
$pdf->Cell(25, 15, 'DRE', 1, 0, 'C');
$pdf->Cell(60, 15, 'Aluno', 1, 0, 'C');
$pdf->Cell(10, 15, 'Nivel', 1, 0, 'C');
$pdf->Cell(100, 15, 'Instituicao', 1, 0, 'C');
$pdf->Cell(60, 15, 'Supervisor', 1, 0, 'C');
$pdf->Ln(15);

for ($i = 0; $i<10; $i++) {
	$pdf->Cell(10, 10, '',1);
	$pdf->Cell(25, 10, '', 1, 'C');
	$pdf->Cell(60, 10, '', 1);
	$pdf->Cell(10, 10, '', 1, 'C');
	$pdf->Cell(100, 10, '', 1, 'C');
	$pdf->Cell(60, 10, '', 1, 'C');
	$pdf->Ln(10);
}

//Close and output PDF document
$pdf->Output('pauta_estagiarios.pdf', 'I'); 

?>
