<?php

// define("FPDF_FONTPATH","/home2/locuss/www/fpdf151/font/");
// define("FPDF","/home2/locuss/www/fpdf151/");

define("FPDF_FONTPATH","/usr/local/htdocs/html/fpdf151/font/");
define("FPDF","/usr/local/htdocs/html/fpdf151/");

// define("FPDF_FONTPATH","/var/www/fpdf151/font/");
// define("FPDF","/var/www/fpdf151/");

require(FPDF."fpdf.php");

include_once("../db.inc");
include_once("../setup.php");
$id_instituicao = $_REQUEST['id_instituicao'];

$sql = "select instituicao from mural_estagio where id=$id_instituicao";
$resultado_instituicao = $db->Execute($sql);
if($resultado_instituicao === false) die ("Não foi possível consultar a tabela mural_estagio");
while(!$resultado_instituicao->EOF) {
    $instituicao = $resultado_instituicao->fields['instituicao'];
    $resultado_instituicao->MoveNext();
}

// Pego os id dos alunos inscritos
$sql = "SELECT id, id_aluno, data FROM `mural_inscricao` WHERE id_instituicao='$id_instituicao' and periodo='". PERIODO_ATUAL . "'";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela mural_inscricao");
$i = 0;
while(!$resultado->EOF) {
		$id = $resultado->fields['id'];
		$id_aluno = $resultado->fields['id_aluno'];
		$data = $resultado->fields['data'];

		// Primeiro busco saber se o aluno esta na tabela de estagiarios
		$sqlAlunos = "select nome, registro, id, telefone, celular, email from alunos where registro=$id_aluno";
		// echo $sqlAlunos . "<br>";
		$resultadoAlunos = $db->Execute($sqlAlunos);
		if($resultadoAlunos === false) die ("Não foi possível consultar a tabela alunos");
		$quantidade = $resultadoAlunos->RecordCount();
		// echo $quantidade . " ";
		// Se nao esta estagiando, vou para a tabela alunosNovos
		if ($quantidade == 0) {
					$sqlAlunosNovos = "select nome, registro, id, telefone, celular, email from alunosNovos where registro=$id_aluno";
					$resultadoAlunosNovos = $db->Execute($sqlAlunosNovos);
					if($resultadoAlunosNovos === false) die ("Não foi possível consultar a tabela alunosNovos");
					while(!$resultadoAlunosNovos->EOF) {
							$nome = $resultadoAlunosNovos->fields['nome'];
							// echo "Novos " . $nome . "<br>";
							// $instituicao = $resultadoAlunosNovos->fields['instituicao'];
							$inscritos[$i]['nome'] = $resultadoAlunosNovos->fields['nome'];
							$inscritos[$i]['id'] = $id;
							$inscritos[$i]['registro'] = $resultadoAlunosNovos->fields['registro'];
							$inscritos[$i]['id'] = $resultadoAlunosNovos->fields['id'];
							$inscritos[$i]['telefone'] = $resultadoAlunosNovos->fields['telefone'];
							$inscritos[$i]['celular'] = $resultadoAlunosNovos->fields['celular'];
							$inscritos[$i]['email'] = $resultadoAlunosNovos->fields['email'];
							$inscritos[$i]['instituicao'] = $resultadoAlunosNovos->fields['instituicao'];
							
							$dataCorrigida = split("-",$data);
							$dataSQL = $dataCorrigida[2] . "-" . $dataCorrigida[1] . "-" . $dataCorrigida[0];
							$inscritos[$i]['data'] = $dataSQL;
							
							$inscritos[$i]['aluno'] = 0;
							$i++;
							$resultadoAlunosNovos->MoveNext();
					}
		// Se esta estagiando pego a informacao
		} else {
		while(!$resultadoAlunos->EOF) {
				$nome = $resultadoAlunos->fields['nome'];
				// echo "Velhos: " . $nome . "<br>";
				// $instituicao = $resultadoAlunos->fields['instituicao'];
				$inscritos[$i]['nome'] = $resultadoAlunos->fields['nome'];			
				$inscritos[$i]['id'] = $id;
				$inscritos[$i]['registro'] = $resultadoAlunos->fields['registro'];
				$inscritos[$i]['id'] = $resultadoAlunos->fields['id'];
				$inscritos[$i]['telefone'] = $resultadoAlunos->fields['telefone'];
				$inscritos[$i]['celular'] = $resultadoAlunos->fields['celular'];
				$inscritos[$i]['email'] = $resultadoAlunos->fields['email'];
				$inscritos[$i]['instituicao'] = $resultadoAlunos->fields['instituicao'];
				
				$dataCorrigida = split("-",$data);
				$dataSQL = $dataCorrigida[2] . "-" . $dataCorrigida[1] . "-" . $dataCorrigida[0];
				$inscritos[$i]['data'] = $dataSQL;
				
				$inscritos[$i]['aluno'] = 1;
				$i++;				
				$resultadoAlunos->MoveNext();
			}
		}
		$resultado->MoveNext();
}

if (sizeof($inscritos) != 0) {
	sort($inscritos);
}

$pdf=new FPDF();
$pdf->Open();

$titulo = "Alunos inscritos para seleção de estágio";
$pdf->SetTitle($titulo);
$pdf->SetAuthor("Coordenação de Estágio - ESS/UFRJ");

$pdf->AddPage();
// $pdf->SetMargins(30,20,30);

$pdf->SetFont("Arial","","20");

// $pdf->Image("minerva.jpg",100,20,20,20,jpg);
// $pdf->Ln(50);
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

// $pdf->Image("LogoESS.jpg",95,120,20,20,jpg);
// $pdf->Ln(10);

$pdf->SetFont("Arial","","20");
$subtitulo = "Alunos inscritos para seleção de estagio: " . $instituicao;
$cabecalho4 = $pdf->GetStringWidth($substitulo);
$pdf->SetX((210-$cabecalho4)/2);
$pdf->MultiCell(0,15,$subtitulo);
$pdf->Ln(20);

$pdf->SetFont("Arial","","15");
$cabecalho5 = $pdf->GetStringWidth("http www.ess.ufrj.br estagio");
$pdf->SetX((210-$cabecalho5)/2);
$pdf->Cell($cabecalho4,9,"http://www.ess.ufrj.br",0,1,'C',0);
$pdf->Ln(5);

$pdf->SetFont("Arial","","15");
$cabecalho5 = $pdf->GetStringWidth("estagio@ess.ufrj.br");
$pdf->SetX((210-$cabecalho5)/2);
$pdf->Cell($cabecalho4,9,"estagio@ess.ufrj.br",0,1,'C',0);
$pdf->Ln(20);

$data = date('d/m/Y');

// A variavel linha define o inicio de cada pagina
$linha = 20;
// A varivel pagina conta a quantidade de registros. Cada quatro registros avança una página
$pagina = 0;

$pdf->SetFont("Arial","","12");
$pdf->SetXY(0,270);
$pdf->Cell(0,0,$data,0,0,"C");

$pdf->AddPage();

$linha = 20; // Define o inicio da página
$pagina = 0; // Conta a quantidade de registros. Cada 20 registros avança una página

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

for($j=0;$j<sizeof($inscritos);$j++) {
	$aluno = $inscritos[$j];
	$pdf->SetXY(15,$linha);
	$pdf->Cell(0,5,$aluno['registro']);

	$pdf->SetXY(40,$linha);
	$tamanho_nome = strlen($aluno['nome']);
	if($tamanho_nome>=25) {
		$nome = substr($aluno['nome'],0,25);
		$pdf->Cell(0,5,$nome);
	} else {
		$pdf->Cell(0,5,$aluno['nome']);
	}
	$pdf->SetXY(95,$linha);
	$pdf->Cell(0,5,$aluno['telefone']);

	$pdf->SetXY(115,$linha);
	$pdf->Cell(0,5,$aluno['celular']);
	
	$pdf->SetXY(135,$linha);
	$pdf->Cell(0,5,$aluno['email']);
	$pdf->Ln(5);
	
	$pagina++;
    $linha = $linha + 10;
	
	if($pagina>=24) {
		$pagina = 0;
		$linha = 20;
		$pdf->SetXY(0,275);
		$pdf->Cell(0,0,$pdf->PageNo(),0,0,"C");
		$pdf->AddPage();
	}
}

$pdf->Output();

?>