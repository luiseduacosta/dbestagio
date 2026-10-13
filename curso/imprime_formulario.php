<?php

require_once(__DIR__ . "/../libphp/models.php");
$id_supervisor = isset($_REQUEST['id_supervisor']) ? (int)$_REQUEST['id_supervisor'] : 0;

include_once("../setup.php");
$turma = (int)TURMA;

// define("FPDF_FONTPATH","/var/www/fpdf151/font/");
// define("FPDF","/var/www/fpdf151/");

// define("FPDF_FONTPATH","/home2/locuss/public_html/fpdf151/font/");
// define("FPDF","/home2/locuss/public_html/fpdf151/");

define("FPDF_FONTPATH", dirname(__DIR__) . "/lib/fpdf/font/");
define("FPDF", dirname(__DIR__) . "/lib/fpdf/");

require(FPDF."fpdf.php");
require_once(__DIR__ . "/../libphp/PDFHelper.php");

$pdf=new PDF_UTF8();
$pdf->AddPage();
// $pdf->SetMargins(30,20,30);
$pdf->SetFont("Arial","","12");

$pdf->Image("minerva.jpg",30,20,20,20,'jpg');
$pdf->Image("LogoESS.jpg",170,20,20,20,'jpg');
$pdf->Ln(5);
$cabecalho1 = $pdf->GetStringWidth("UNIVERSIDADE FEDERAL DO RIO DE JANEIRO");
$pdf->SetX((210-$cabecalho1)/2);
$pdf->Cell($cabecalho1,9,"UNIVERSIDADE FEDERAL DO RIO DE JANEIRO",0,1,'C',0);
$pdf->Ln(2);

$cabecalho2 = $pdf->GetStringWidth("Escola de Serviço Social");
$pdf->SetX((210-$cabecalho2)/2);
$pdf->Cell($cabecalho2,9,"Escola de Serviço Social",0,1,'C',0);
$pdf->Ln(2);

$cabecalho3 = $pdf->GetStringWidth("Coordenação de Estágio");
$pdf->SetX((210-$cabecalho3)/2);
$pdf->Cell($cabecalho3,9,"Coordenação de Estágio",0,1,'C',0);
$pdf->Ln(2);

$cabecalho4 = $pdf->GetStringWidth("Inscrição para o $turma curso atualização de supervisores ");
$pdf->SetX((210-$cabecalho4)/2);
$pdf->Cell($cabecalho4,9,"Inscricao para o $turma º curso atualização de supervisores",0,1,'C',0);
$pdf->Ln(2);

// Initialize variables to prevent undefined warnings
$num_inscricao = $nome = $endereco = $bairro = $municipio = $cep = $telefone = $email = '';
$escola = $ano_formatura = $cress = $outros_estudos = $area_curso = $ano_curso = $cargo = '';
$instituicao = $inst_endereco = $inst_bairro = $inst_municipio = $inst_cep = $inst_telefone = '';
$inst_fax = $beneficio = $fim_de_semana = '';

$sql = "select " .
" s.num_inscricao, s.nome, s.endereco, s.bairro, s.municipio, s.cep, s.telefone, s.email, " .
" s.escola, s.ano_formatura, s.cress, s.outros_estudos, s.area_curso, s.ano_curso, s.cargo, " .
" i.instituicao, i.endereco as inst_endereco, i.bairro as inst_bairro, i.municipio as inst_municipio, " .
" i.cep as inst_cep, i.telefone as inst_telefone, i.fax as inst_fax, i.beneficio, i.fim_de_semana " .
" from curso_inscricao_supervisor as s, curso_inscricao_instituicao as i, curso_inst_super as j " .
" where s.id=j.id_supervisor and j.id_instituicao=i.id and s.id=? " .
" and s.curso_turma=?";

// Use Supervisor model (curso tables are related to supervisors)
$resultado = Supervisor::query($sql, array($id_supervisor, $turma));
if($resultado === false) die ("Não foi possível consultar as tabelas de inscricao");
foreach ($resultado as $item)
{
  $num_inscricao  = $item->num_inscricao;
  $nome           = $item->nome;
  $endereco       = $item->endereco;
  $bairro         = $item->bairro;
  $municipio      = $item->municipio;
  $cep            = $item->cep;
  $telefone       = $item->telefone;
  $email          = $item->email;
  $escola         = $item->escola;
  $ano_formatura  = $item->ano_formatura;
  $cress          = $item->cress;
  $outros_estudos = $item->outros_estudos;
  $area_curso     = $item->area_curso;
  $ano_curso      = $item->ano_curso;
  $cargo          = $item->cargo;
  $instituicao    = $item->instituicao;
  $inst_endereco  = $item->inst_endereco;
  $inst_bairro    = $item->inst_bairro;
  $inst_municipio = $item->inst_municipio;
  $inst_cep       = $item->inst_cep;
  $inst_telefone  = $item->inst_telefone;
  $inst_fax       = $item->inst_fax;
  $beneficio      = $item->beneficio;
  $fim_de_semana  = $item->fim_de_semana;
}

if($fim_de_semana == 0)
    $fim_de_semana = "Não";
elseif($fim_de_semana == 1)
    $fim_de_semana = "Sim";
elseif($fim_de_semana == 2)
    $fim_de_semana = "Parcialmente";
else
    $fim_de_semana = "s/d";

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"No. de inscrição:");
$pdf->SetX(70);
$pdf->Cell(0,0,$num_inscricao);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Nome: ");
$pdf->SetX(70);
$pdf->Cell(0,0,$nome);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Endereço:");
$pdf->SetX(70);
$pdf->Cell(0,0,$endereco);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Bairro:");
$pdf->SetX(70);
$pdf->Cell(0,0,$bairro);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Muncípio:");
$pdf->SetX(70);
$pdf->Cell(0,0,$municipio);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"CEP:");
$pdf->SetX(70);
$pdf->Cell(0,0,$cep);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Telefone:");
$pdf->SetX(70);
$pdf->Cell(0,0,$telefone);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"E-mail:");
$pdf->SetX(70);
$pdf->Cell(0,0,$email);


$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Escola:");
$pdf->SetX(70);
$pdf->Cell(0,0,$escola);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Ano da formatura:");
$pdf->SetX(70);
$pdf->Cell(0,0,$ano_formatura);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Registro no CRESS:");
$pdf->SetX(70);
$pdf->Cell(0,0,$cress);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Outros estudos:");
$pdf->SetX(70);
$pdf->Cell(0,0,$outros_estudos);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Área:");
$pdf->SetX(70);
$pdf->Cell(0,0,$area_curso);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Ano em que foi concluído:");
$pdf->SetX(80);
$pdf->Cell(0,0,$ano_curso);


$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Cargo que ocupa na instituição:");
$pdf->SetX(80);
$pdf->Cell(0,0,$cargo);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Instituição:");
$pdf->SetX(70);
$pdf->Cell(0,0,$instituicao);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Endereço:");
$pdf->SetX(70);
$pdf->Cell(0,0,$inst_endereco);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Bairro:");
$pdf->SetX(70);
$pdf->Cell(0,0,$inst_bairro);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Município:");
$pdf->SetX(70);
$pdf->Cell(0,0,$inst_municipio);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"CEP:");
$pdf->SetX(70);
$pdf->Cell(0,0,$inst_cep);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Telefone:");
$pdf->SetX(70);
$pdf->Cell(0,0,$inst_telefone);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Fax:");
$pdf->SetX(70);
$pdf->Cell(0,0,$inst_fax);


$pdf->Ln(10);
$pdf->SetX(20);
$pdf->Cell(0,0,"Benefícios:");
$pdf->SetX(70);
$pdf->Cell(0,0,$beneficio);

$pdf->Ln(7);
$pdf->SetX(20);
$pdf->Cell(0,0,"Estágio no final de semana:");
$pdf->SetX(80);
$pdf->Cell(0,0,$fim_de_semana);

$pdf->Output();

?>