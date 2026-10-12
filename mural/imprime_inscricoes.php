<?php

define("FPDF_FONTPATH", dirname(__DIR__) . "/lib/fpdf/font/");
define("FPDF", dirname(__DIR__) . "/lib/fpdf/");

require(FPDF."fpdf.php");
require_once(__DIR__ . "/../libphp/PDFHelper.php");

include_once("../setup.php");
require_once(__DIR__ . "/../libphp/Mural.php");
require_once(__DIR__ . "/../libphp/Inscricao.php");

if (!isset($muralestagio_id)) {
    $muralestagio_id = $_REQUEST['muralestagio_id'] ?? null;
}
$muralestagio_id = (int)$muralestagio_id;
if ($muralestagio_id <= 0) {
    die("Faltou passar o parâmetro muralestagio_id");
}

// 1) instituicao da oferta (defesa em profundidade: filtra por PERIODO_ATUAL,
//    impede imprimir PDF de cartazes de períodos passados).
$cartaz = Mural::cartazPorId($muralestagio_id, PERIODO_ATUAL);
if ($cartaz === null) {
    die("Não há cartaz com id $muralestagio_id no período atual");
}
$instituicao = $cartaz['instituicao'];

// 2) inscritos em uma unica consulta com JOIN (sem N+1).
$inscritos = Inscricao::listar(PERIODO_ATUAL, $muralestagio_id);

// Ordena por nome do aluno (igual ao sort() do script antigo sobre o 1o
// campo, que era 'nome').
usort($inscritos, function ($a, $b) {
    return strcasecmp($a['aluno_nome'] ?? '', $b['aluno_nome'] ?? '');
});

// ------------------------------------------------------------------
// Geracao do PDF
// ------------------------------------------------------------------

$pdf = new PDF_UTF8();

$titulo = "Alunos inscritos para seleção de estágio";
$pdf->SetTitle($titulo);
$pdf->SetAuthor("Coordenação de Estágio - ESS/UFRJ");

$pdf->AddPage();

$pdf->SetFont("Arial","","20");

$cabecalho1 = $pdf->GetStringWidth("UNIVERSIDADE FEDERAL DO RIO DE JANEIRO");
$pdf->SetY(20);
$pdf->SetX((210-$cabecalho1)/2);
$pdf->Cell($cabecalho1,9,"UNIVERSIDADE FEDERAL DO RIO DE JANEIRO",0,1,'C',0);
$pdf->Ln(25);

$cabecalho2 = $pdf->GetStringWidth("Escola de Serviço Social");
$pdf->SetX((210-$cabecalho2)/2);
$pdf->Cell($cabecalho2,9,"Escola de Serviço Social",0,1,'C',0);
$pdf->Ln(40);

$pdf->SetFont("Arial","","25");
$cabecalho3 = $pdf->GetStringWidth("Coordenação de Estágio e Extensão");
$pdf->SetX((210-$cabecalho3)/2);
$pdf->Cell($cabecalho3,9,"Coordenação de Estágio e Extensão",0,1,'C',0);
$pdf->Ln(20);

$pdf->SetFont("Arial","","14");
$subtitulo = "Alunos inscritos para seleção de estagio: " . $instituicao;
$cabecalho4 = $pdf->GetStringWidth($subtitulo);
$pdf->SetX((210-$cabecalho4)/2);
$pdf->MultiCell(0,15,$subtitulo);
$pdf->Ln(20);

$pdf->SetFont("Arial","","12");
$cabecalho5 = $pdf->GetStringWidth("http www.ess.ufrj.br estagio");
$pdf->SetX((210-$cabecalho5)/2);
$pdf->Cell($cabecalho4,9,"http://www.ess.ufrj.br",0,1,'C',0);
$pdf->Ln(5);

$pdf->SetFont("Arial","","12");
$cabecalho5 = $pdf->GetStringWidth("estagio@ess.ufrj.br");
$pdf->SetX((210-$cabecalho5)/2);
$pdf->Cell($cabecalho4,9,"estagio@ess.ufrj.br",0,1,'C',0);
$pdf->Ln(20);

$data = date('d/m/Y');

$pdf->SetFont("Arial","","10");
$pdf->SetXY(0,270);
$pdf->Cell(0,0,$data,0,0,"C");

$pdf->AddPage();

$linha = 20;
$pagina = 0;

$pdf->SetXY(15,$linha);
$pdf->Cell(0,5,"Registro");
$pdf->SetXY(40,$linha);
$pdf->Cell(0,5,"Nome");
$pdf->SetXY(95,$linha);
$pdf->Cell(0,5,"Telefone");
$pdf->SetXY(115,$linha);
$pdf->Cell(0,5,"Celular");
$pdf->SetXY(135,$linha);
$pdf->Cell(0,5,"E-mail");
$linha = $linha + 7;

foreach ($inscritos as $inscrito) {
    $pdf->SetXY(15,$linha);
    $pdf->Cell(0,3,$inscrito['registro']);

    $pdf->SetXY(40,$linha);
    $nome = strlen($inscrito['aluno_nome']) >= 25
        ? substr($inscrito['aluno_nome'], 0, 25)
        : $inscrito['aluno_nome'];
    $pdf->Cell(0,3,strip_tags(html_entity_decode($nome)));

    $pdf->SetXY(95,$linha);
    $pdf->Cell(0,3,strip_tags(html_entity_decode($inscrito['aluno_telefone'])));

    $pdf->SetXY(115,$linha);
    $pdf->Cell(0,3,strip_tags(html_entity_decode($inscrito['aluno_celular'])));

    $pdf->SetXY(135,$linha);
    $pdf->Cell(0,3,strip_tags(html_entity_decode($inscrito['aluno_email'])));
    $pdf->Ln(3);

    $pagina++;
    $linha = $linha + 6;

    if ($pagina >= 35) {
        $pagina = 0;
        $linha = 20;
        $pdf->SetXY(0,275);
        $pdf->Cell(0,0,$pdf->PageNo(),0,0,"C");
        $pdf->AddPage();
    }
}

$arquivo = "estagio" . $muralestagio_id . ".pdf";

$pdf->Output("/tmp/$arquivo");

echo "<html><script>document.location='/tmp/$arquivo';</script></html>";
