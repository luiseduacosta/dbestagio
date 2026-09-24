<?php
/*
 * Created on 13/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once('../setup.php');


$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); 
// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false); 
// 
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Coordenação de Estágio');
$pdf->SetTitle('Pauta de alunos estagiários');
$pdf->SetSubject('Alunos estagiários');
$pdf->SetKeywords('Estágio, Serviço Social');

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


$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : NULL;
$prof_id = isset($_REQUEST['id_professor']) ? $_REQUEST['id_professor'] : NULL;
$professor = isset($_REQUEST['professor']) ? $_REQUEST['professor'] : NULL;
$id_area = isset($_REQUEST['id_area']) ? $_REQUEST['id_area'] : NULL;

// echo $periodo . " " . $prof_id . "<br>";
/* Capturo os valores para a área */
$sql  = "select areas.area from estagiarios ";
$sql .= " join instituicoes on estagiarios.instituicao_id = instituicoes.id ";
$sql .= " left join areas on instituicoes.area = areas.id ";
$sql .=	" where estagiarios.periodo = '$periodo' "; 
$sql .= " and instituicoes.area = $id_area ";
$sql .=	" and estagiarios.professor_id = '$prof_id' ";
// echo $sql . "<br>";

$res = $db->Execute($sql);
$area = $res->fields['area'];

// $pdf->SetXY(15,25);

$titulo = <<<EOD
<p style="font-family:arial; color:red; font-size:16;">
Período: $periodo - Professor(a): $professor - Área: $area
</p>
EOD;

$pdf->writeHTML($titulo, true, false, false, false, '');

$tbl = <<<EOD
<table border="1" cellpadding="3" cellspacing="1">
<theader>
<tr>
<td width="10%" style="font-weight:bold; text-align:center;"><strong>Registro</strong></td>
<td width="20%"  style="font-weight:bold; text-align:center;"><strong>Estudante</strong></td>
<td width="5%"  style="font-weight:bold; text-align:center;"><strong>Nível</strong></td>
<td width="35%"  style="font-weight:bold; text-align:center;"><strong>Instituição</strong></td>
<td width="30%"  style="font-weight:bold; text-align:center;"><strong>Supervisor</strong></td>
</tr>
</theader>
<tbody>
EOD;

$sql  = "select alunos.registro, alunos.nome as aluno, estagiarios.nivel, estagiarios.professor_id as id_professor, instituicoes.instituicao, supervisores.nome as supervisor, estagiarios.periodo from estagiarios ";
$sql .= " join alunos on estagiarios.registro = alunos.registro ";
$sql .= " join instituicoes on estagiarios.instituicao_id = instituicoes.id ";
$sql .= " left join supervisores on estagiarios.supervisor_id = supervisores.id ";
$sql .=	" where estagiarios.periodo = '$periodo' "; 
$sql .= " and instituicoes.area = $id_area ";
$sql .=	" and estagiarios.professor_id = '$prof_id' ";
$sql .= " order by alunos.nome ";
// echo $sql . "<br>";

$res = $db->Execute($sql);

while (!$res->EOF) {
	$registro = $res->fields['registro'];
	$aluno = $res->fields['aluno'];
	$supervisor = $res->fields['supervisor'];
	$id_prof = $res->fields['id_professor'];
	$instituicao = $res->fields['instituicao'];
	$periodo = $res->fields['periodo'];
	$nivel = $res->fields['nivel'];

$tbl .= <<<EOD
<tr>
<td style="text-align:center">$registro</td>
<td>$aluno</td>
<td style="text-align:center">$nivel</td>
<td>$instituicao</td>
<td>$supervisor</td>
</tr>
EOD;
        
        $res->MoveNext();

}

$tbl .= <<<EOD
</tbody>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

$tabela = <<<EOD
<p style="font-size:16">Acrescentar aqui novos alunos para incluir na pauta</p>
<br>
<table border="1" cellpadding="3" cellspacing="1">
<theader>
<tr>
<td width="10%" style="font-weight:bold; text-align:center">Registro</td>
<td width="20%" style="font-weight:bold; text-align:center">Aluno</td>
<td width="5%" style="font-weight:bold; text-align:center">Nível</td>
<td width="35%" style="font-weight:bold; text-align:center">Instituição</td>
<td width="30%" style="font-weight:bold; text-align:center">Supervisor</td>
</tr>
</theader>
<tbody>
EOD;

for ($i = 0; $i<10; $i++) {

	$tabela .= <<<EOD
<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr>
EOD;

    }

$tabela .= <<<EOD
</tbody>
</table>
EOD;

$data = date('d/m/Y');

$tabela .= <<<EOD
<br>
<p style="text-align:rigth">Rio de Janeiro, $data</p>
EOD;

$pdf->writeHTML($tabela, true, false, true, false, '');
// echo $tabela;
//Close and output PDF document
$pdf->Output('pauta_estagiarios.pdf', 'I'); 

?>
