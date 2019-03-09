<?php

// Intranet
define("FPDF_FONTPATH","/usr/local/htdocs/html/fpdf153/font/");
define("FPDF","/usr/local/htdocs/html/fpdf153/");

require(FPDF."fpdf.php");

$pdf=new FPDF();
$pdf->Open();

$titulo = "Cadastro de campos de estágio";
$pdf->SetTitle($titulo);
$pdf->SetAuthor("Coordenação de Estágio & Extensão - ESS/UFRJ");

$pdf->AddPage();
// $pdf->SetMargins(30,20,30);

$pdf->SetFont("Arial","","20");

$pdf->Image("minerva.jpg", 100, 20, 20, 20, jpg);
$pdf->Ln(50);
$cabecalho1 = $pdf->GetStringWidth("UNIVERSIDADE FEDERAL DO RIO DE JANEIRO");
$pdf->SetX((210-$cabecalho1)/2);
$pdf->Cell($cabecalho1,9,"UNIVERSIDADE FEDERAL DO RIO DE JANEIRO", 0, 1, 'C', 0);
$pdf->Ln(30);

$cabecalho2 = $pdf->GetStringWidth("Escola de Serviço Social");
$pdf->SetX((210-$cabecalho2)/2);
$pdf->Cell($cabecalho2,9,"Escola de Serviço Social", 0, 1, 'C', 0);
$pdf->Ln(50);

$pdf->SetFont("Arial","","30");
$cabecalho3 = $pdf->GetStringWidth("Coordenação de Estágio");
$pdf->SetX((210-$cabecalho3)/2);
$pdf->Cell($cabecalho3,9,"Coordenação de Estágio", 0, 1, 'C', 0);
$pdf->Ln(20);

$pdf->Image("LogoESS.jpg",95,120,20,20,jpg);
$pdf->Ln(10);

$cabecalho4 = $pdf->GetStringWidth("Catálogo de campos de estágio");
$pdf->SetX((210-$cabecalho4)/2);
$pdf->Cell($cabecalho4, 9, "Catálogo de campos de estágio", 0, 1, 'C', 0);
$pdf->Ln(20);

$pdf->SetFont("Arial","","15");
$cabecalho5 = $pdf->GetStringWidth("http www.ess.ufrj.br estagio");
$pdf->SetX((210-$cabecalho5)/2);
$pdf->Cell($cabecalho5, 9, "http://www.ess.ufrj.br", 0, 1, 'C', 0);
$pdf->Ln(20);

$data = date('d/m/Y');

$pdf->SetFont("Arial","","12");
$pdf->SetXY(0,270);
$pdf->Cell(0,0,$data, 0, 0, "C");

include_once("../../setup.php");

$sql = "select
e.id,
e.instituicao,
e.endereco,
e.telefone,
e.fax,
e.beneficio,
case e.fim_de_semana
    when 0 then 'Não'
    when 1 then 'Sim'
    when 2 then 'Parcialmente'
    else 'Não'
    end as f_semana,
a.area
from estagio as e, areas_estagio as a
where e.area=a.id
order by instituicao";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela estagio e/ou areas_estagio");

$pdf->AddPage();

// A variavel linha define o inicio de cada pagina
$linha = 20;
// A varivel pagina conta a quantidade de registros. Cada quatro registros avança una página
$pagina = 0;
while (!$resultado->EOF) {
    $area           = $resultado->fields["area"];
    $instituicao    = $resultado->fields["instituicao"];
    $endereco       = $resultado->fields["endereco"];
    $telefone       = $resultado->fields["telefone"];
    $fax            = $resultado->fields["fax"];
    $beneficios     = $resultado->fields["beneficio"];
    $fim_de_semana  = $resultado->fields["f_semana"];
    $id_instituicao = $resultado->fields["id"];

    $sqlPeriodo = "select max(periodo) as periodo from estagiarios where id_instituicao = $id_instituicao";
    $resultadoPeriodo = $db->Execute($sqlPeriodo);
    if($resultadoPeriodo === false) die ("Não foi possível consultar a tabela estagiarios");
    while (!$resultadoPeriodo->EOF) {
        $turma = $resultadoPeriodo->fields["periodo"];
	$resultadoPeriodo->MoveNext();
    }

    $instituicao = trim($instituicao);
    $instituicao = ucwords($instituicao);
    $endereco = trim($endereco);

    $pdf->SetXY(30,$linha);
    $pdf->Cell(0,5,"Área:");
    $pdf->SetX(65);
    $pdf->Cell(0,5,$area);
    $pdf->Ln(5);

    $pdf->SetX(30);
    $pdf->Cell(0,5,"Instituição:");
    $pdf->SetX(65);
    $tamanho_instituicao = $pdf->GetStringWidth($instituicao);
    if ($tamanho_instituicao >= 50)
    {
        $pdf->MultiCell(0,4,$instituicao);
		$pdf->Ln(1);
    }
    else
    {
    	$pdf->Cell(0,5,$instituicao);
		$pdf->Ln(5);
    }

    $pdf->SetX(30);
    $pdf->Cell(0,5,"Endereço:");
    $pdf->SetX(65);
    $tamanho_endereco = $pdf->GetStringWidth($endereco);
    if ($tamanho_endereco >= 50)
    {
        $pdf->MultiCell(0,4,$endereco);
	$pdf->Ln(1);
    }
    else
    {
		$pdf->Cell(0,5,$endereco);
		$pdf->Ln(5);
    }

    $pdf->SetX(30);
    $pdf->Cell(0,5,"Telefone:");
    $pdf->SetX(65);
    $pdf->Cell(0,5,$telefone);
    $pdf->Ln(5);

    $pdf->SetX(30);
    $pdf->Cell(0,5,"Fax:");
    $pdf->SetX(65);
    $pdf->Cell(0,5,$fax);
    $pdf->Ln(5);

    $pdf->SetX(30);
    $pdf->Cell(0,5,"Benefícios:");
    $pdf->SetX(65);
    $pdf->Cell(0,5,$beneficios);
    $pdf->Ln(5);

    $pdf->SetX(30);
    $pdf->Cell(0,5,"Fim de semana:");
    $pdf->SetX(65);
    $pdf->Cell(0,5,$fim_de_semana);
    $pdf->Ln(5);

    $pdf->SetX(30);
    $pdf->Cell(0,5,"Turma:");
    $pdf->SetX(65);
    $pdf->Cell(0,5,$turma);
    $pdf->Ln(5);

    $pagina++;
    $linha = $linha + (50 + ($quantidade_supervisores * 5));

    $resultado->MoveNext();

    if($pagina >= 5)
    {
		$pagina = 0;
		$linha  = 20;
		$pdf->SetXY(0,275);
		$pdf->Cell(0,0,$pdf->PageNo(),0,0,"C");
		$pdf->AddPage();
    }

}

$pdf->Output();

?>
