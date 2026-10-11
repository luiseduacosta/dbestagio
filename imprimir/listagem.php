<?php

require_once("../autentica.inc"); // Carrega setup.php e exige login

// Intranet
define("FPDF_FONTPATH", dirname(__DIR__) . "/lib/fpdf/font/");
define("FPDF", dirname(__DIR__) . "/lib/fpdf/");

require(FPDF."fpdf.php");
require_once(__DIR__ . "/../libphp/PDFHelper.php");

$pdf = new PDF_UTF8();

$titulo = "Catálogo de instituições conveniadas";
$pdf->SetTitle($titulo);
$pdf->SetAuthor("Coordenação de Estágio & Extensão - ESS/UFRJ");

$pdf->AddPage();
// $pdf->SetMargins(30,20,30);

$pdf->SetFont("Arial","","20");

$pdf->Image("minerva.jpg", 100, 20, 20, 20, 'jpg');
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

$pdf->Image("LogoESS.jpg",95,120,20,20,'jpg');
$pdf->Ln(10);

$cabecalho4 = $pdf->GetStringWidth("Catálogo de instituições conveniadas");
$pdf->SetX((210-$cabecalho4)/2);
$pdf->Cell($cabecalho4, 9, "Catálogo de instituições conveniadas", 0, 1, 'C', 0);
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

$sql = "select
e.id,
e.instituicao,
e.endereco,
e.telefone,
e.beneficios,
case e.fim_de_semana
    when 0 then 'Não'
    when 1 then 'Sim'
    when 2 then 'Parcialmente'
    else 'Não'
    end as f_semana,
a.area
from instituicoes as e, areas as a
where e.area_id=a.id
order by instituicao";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela instituicoes e/ou areas");

$pdf->AddPage();

// A variavel linha define a posicao vertical dentro da pagina atual
$linha = 20;
// A variavel pagina conta as instituicoes ja renderizadas na pagina atual (quebra a cada 5)
$pagina = 0;
while (!$resultado->EOF) {
    // Antes de renderizar, fecha a anterior e abre nova pagina se ja temos 5 instituicoes
    if ($pagina >= 5) {
        $pdf->SetXY(0,275);
        $pdf->Cell(0,0,$pdf->PageNo(), 0, 0, "C");
        $pdf->AddPage();
        $pagina = 0;
        $linha = 20;
    }

    $area           = $resultado->fields["area"];
    $instituicao    = $resultado->fields["instituicao"];
    $endereco       = $resultado->fields["endereco"];
    $telefone       = $resultado->fields["telefone"];
    $beneficios     = $resultado->fields["beneficios"];
    $fim_de_semana  = $resultado->fields["f_semana"];
    $id             = (int)$resultado->fields["id"];

    // Conta os supervisores distintos que ja registraram estagiarios nesta instituicao
    // para dimensionar o espaco do bloco no PDF (varia conforme a quantidade de supervisores)
    $quantidade_supervisores = (int)$db->GetOne(
        "SELECT COUNT(DISTINCT supervisor_id) FROM estagiarios WHERE instituicao_id = ?",
        array($id)
    );

    // Turma mais recente com estagiarios nesta instituicao
    $turma = $db->GetOne(
        "SELECT MAX(periodo) FROM estagiarios WHERE instituicao_id = ?",
        array($id)
    );

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
    $linha = $linha + 50;    
    // $linha = $linha + 50 + ($quantidade_supervisores * 5);

    $resultado->MoveNext();

}

// Imprime o numero na ultima pagina (nao coberto pelo loop)
$pdf->SetXY(0,275);
$pdf->Cell(0,0,$pdf->PageNo(), 0, 0, "C");

$pdf->Output();

?>