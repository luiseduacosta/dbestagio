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
$pdf->SetFont('times', '', 12);

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

// $pdf->SetXY(15,25);

$tabela =
"<p style='font-size:14px'>Alunos estagiarios: periodo $periodo; professor(a) $professor</p>" .
"<table border='1' cellpadding='3' cellspacing='0'>" .
"<theader>" .
"<tr>" .
"<td width='15px'>X</td>" .
"<td width='60px' style='text-align:center'>Registro</td>" .
"<td width='170px'>Aluno</td>" .
"<td width='35px' style='text-align:center'>Nivel</td>" .
"<td width='300px'>Instituicao</td>" .
"<td width='170px'>Supervisor</td>" .
"</tr>" .
"</theader>" .
"<tbody>";

while (!$res->EOF) {
	$registro = $res->fields['registro'];
	$aluno = $res->fields['aluno'];
	$supervisor = $res->fields['supervisor'];
	$id_prof = $res->fields['id_professor'];
	$instituicao = $res->fields['instituicao'];
	$periodo = $res->fields['periodo'];
	$nivel = $res->fields['nivel'];
	
	$tabela .= "<tr>" .
			"<td width='15px'>&nbsp;</td>" .
			"<td width='60px' style='text-align:center'>$registro</td>" .
			"<td width='170px'>$aluno</td>" .
			"<td width='35px' style='text-align:center'>$nivel</td>" .
			"<td width='300px'>$instituicao</td>" .
			"<td width='170px'>$supervisor</td>" .
			"</tr>";
	$res->MoveNext();
}

$tabela .= "</tbody>" .
		"</table>";


$tabela .= "<br>" .
		"<p style='font-size:14px'>Acrescentar aqui novos alunos para incluir na pauta</p>";

$tabela .=
"<table border='1' cellpadding='3' cellspacing='0'>" .
"<theader>" .
"<tr>" .
"<td width='15px'>X</td>" .
"<td width='60px' style='text-align:center'>Registro</td>" .
"<td width='170px'>Aluno</td>" .
"<td width='35px' style='text-align:center'>Nivel</td>" .
"<td width='300px'>Instituicao</td>" .
"<td width='170px'>Supervisor</td>" .
"</tr>" .
"</theader>" .
"<tbody>";

for ($i = 0; $i<10; $i++) {

	$tabela .= "<tr>" .
	"<td width='15px'>&nbsp;</td> " .
	"<td width='60px'>&nbsp;</td> " .
	"<td width='170px'>&nbsp;</td> " .
	"<td width='35px'>&nbsp;</td> " .
	"<td width='300px'>&nbsp;</td> " .
	"<td width='170px'>&nbsp;</td> " .
	"</tr>";
}

$tabela .= "</tbody>" .
		"</table>";

$data = date('d/m/Y');

$tabela .="<br>" .
		"<p style='text-align:rigth'>Rio de Janeiro, $data</p>";
		
$pdf->SetFont('times', '', 10);

$pdf->writeHTML($tabela, true, 0, true, 0);

//Close and output PDF document
$pdf->Output('pauta_estagiarios.pdf', 'I'); 

?>
