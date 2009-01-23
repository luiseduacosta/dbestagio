<?php

require_once("../setup.php");

// Data de hoje
$dia = date("d");
$mes = date("m");
$ano = date("Y");

$registro = isset($_GET['registro']) ? $_GET['registro'] : NULL;
$nome = isset($_GET['nome']) ? $_GET['nome'] : NULL;
$classificacao = isset($_GET['classificacao']) ? $_GET['classificacao'] : NULL;
$nivel_romano = isset($_GET['nivel_romano']) ? $_GET['nivel_romano'] : NULL;
$instituicao = isset($_GET['instituicao']) ? $_GET['instituicao'] : NULL;
$supervisor = isset($_GET['supervisor']) ? $_GET['supervisor'] : NULL;
$cress = isset($_GET['cress']) ? $_GET['cress'] : NULL;

if (empty($supervisor)) $supervisor = " __________________________________________________";

// Classificacao do termo de compromisso: novo ou renovacao
if ($classificacao == 0) {
	$classe = ""; // Muda fora de regimento
} elseif ($classificacao == 1) {
	$classe = ""; // Mudanca regimental
} elseif ($classificacao == 2) {
	$classe = ""; // Poderia mudar e nao muda
} elseif ($classificacao == 3) {
	$classe = "renovação"; // Nao muda e nao tinha que mudar
}
// echo $classe . "<br>";
// die;
// echo "Classificacao " . $classificacao . " classe " . $classe . "<br>";

// die;

require(FPDF."fpdf.php");

$pdf = new FPDF("P","mm","A4");

$pdf->Open();

$pdf->AddPage();
$pdf->SetMargins(15,15,15);
$pdf->SetFont("Arial","B","12");
// $pdf->Image("minerva.jpg",100,20,20,20,jpg);
// $pdf->Ln(2);
$cabecalho1 = $pdf->GetStringWidth("UNIVERSIDADE FEDERAL DO RIO DE JANEIRO");
$pdf->SetX((210-$cabecalho1)/2);
$pdf->Cell($cabecalho1,3,"UNIVERSIDADE FEDERAL DO RIO DE JANEIRO",0,1,'C',0);
$pdf->Ln(2);

$cabecalho2 = $pdf->GetStringWidth("Escola de Serviço Social");
$pdf->SetX((210-$cabecalho2)/2);
$pdf->Cell($cabecalho2,3,"Escola de Serviço Social",0,1,'C',0);
$pdf->Ln(2);

$cabecalho3 = $pdf->GetStringWidth("Coordenação de Estágio");
$pdf->SetX((210-$cabecalho3)/2);
$pdf->Cell($cabecalho3,3,"Coordenação de Estágio",0,1,'C',0);
$pdf->Ln(2);

$cabecalho3 = $pdf->GetStringWidth("Termo de compromisso (estagio:  )");
$pdf->SetX((210-$cabecalho3)/2);
$pdf->Cell($cabecalho3,3,"Termo de Compromisso (Estágio $nivel_romano $classe)",0,1,'C',0);
$pdf->Ln(2);

$texto0 = "
O presente TERMO DE COMPROMISSO DE ESTÁGIO que entre si assinam Coordenação de Estágio da Escola de Serviço Social/UFRJ/Estudante " . strtoupper($nome) . ", instituição ". $instituicao . " e Supervisor(a) AS. ". strtoupper($supervisor) . ", visa estabelecer condições gerais que regulam a realização de ESTAGIO CURRICULAR. Atividade obrigatória para a conclusão da Graduação em Serviço Social. Ficam estabelecidas entre as partes as seguintes condições básicas para a realização do estágio:
";

$texto1 = "
Art. 01. As atividades a serem desenvolvidas pelo estagiário, deverão ser compatíveis com o curso de Serviço Social, envolvem observação, estudos, elaboração de projetos e realização de leituras e atividades práticas.
Art. 02. A permanência em cada campo de estágio deverá ser de no mínimo dois semestres letivos consecutivos. A quebra deste contrato, deverá ser precedida de apresentação de solicitação formal à Coordenação de Estágio, com no mínimo 1 mês de antes do término do período letivo em curso. Contendo parecer da supervisora e do professor de OTP.
Art. 03. Em caso de demissão do supervisor, ou a ocorrência de férias deste profissional ao longo do período letivo, outro assistente social deverá ser imediatamente indicado para supervisão técnica do estagiário.
";

$texto2 = "
Art. 04. De acordo com a orientação geral da Universidade do Rio de Janeiro, no que concerne à estágios, e o currículo da Escola de Serviço Social, implantado em 2001. O estágio será realizado por um período de, no mínimo 120 horas/semestre, não podendo ultrapassar 20h semanais.
Art. 05. Será indicado pelos Departamentos da ESS, um professor para acompanhamento acadêmico referente a área temática da instituição que o aluno realizará o seu estágio.
Art. 06. A Escola de Serviço Social fornecerá à Instituição informações e declarações solicitadas, consideradas necessárias ao bom andamento do estágio curricular.
";

$texto3 = "
Art. 07. O estágio será realizado no âmbito da unidade concedente onde deve existir um Assistente Social responsável pelo projeto desenvolvido pelo Serviço Social. As atividades de estágio serão realizadas em horário compatível com as atividades escolares do estagiário e com as normas vigentes no âmbito da unidade concedente.
Art. 08. A Coordenação de Estágio/ESS deve ser informada com prazo de 01 (um) mês de antecedência o afastamento do supervisor do campo de estágio e a indicação do seu substituto.
";

$texto4 = "
Art. 09. É de responsabilidade do Assistente Social supervisor o acompanhamento e supervisão sistemática do processo vivenciado pelo aluno durante o período de estágio.
Art. 10. No final de cada mês o supervisor atestará á unidade de ensino, em formulário próprio, a carga horária cumprida pelo estagiário.
Art. 11. No final de cada período letivo o supervisor encaminhará, ao professor da disciplina de Orientação e Treinamento Profissional, avaliação do processo vivenciado pelo aluno durante o semestre. Instrumento este utilizado pelo professor na avaliação final do aluno.
";

$texto5 = "
Art. 12. Cabe ao estagiário cumprir o horário acordado com a unidade para o desempenho das atividades definidas no Plano de Estágio, observando os princípios éticos que rege o Serviço Social. São considerados motivos justos ao não cumprimento da programação, as obrigações escolares do estagiário que devem ser comunicadas, ao supervisor, em tempo hábil.
Art. 13. 0 aluno se compromete a cuidar e manter sigilo em relação à documentação, da unidade campo de estágio, mesmo após o seu desligamento.
Art. 14. O aluno deverá cumprir com responsabilidade e assiduidade os compromisso assumidos junto ao acampo de estágio, independente do calendário e férias acadêmicas.
Art. 15. O período de permanência do aluno no campo de estágio se dará de acordo com o contrato formal ou informal assumido com o supervisor.
Art. 16. O presente Termo de Compromisso terá validade de $validade1 a $validade2, correspondente ao Estagio ". $nivel_romano .". Sua interrupção antes do período previsto, acarretará prejuízo para o aluno na sua avaliação acadêmica.
Art. 17. Os casos omissos serão encaminhados à Coordenação de Estágio para serem dirimidos.
";

$pdf->SetFont("Arial","","10");
$pdf->Multicell(0,4,$texto0);

$pdf->Ln(1);
$pdf->SetFont("Arial","B","9");
$pdf->Cell(0,5,"Das Partes");
$pdf->Ln(1);
$pdf->SetFont("Arial","","10");
$pdf->MultiCell(0,4,$texto1);

$pdf->Ln(1);
$pdf->SetFont("Arial","B","9");
$pdf->Cell(0,5,"Da ESS");
$pdf->Ln(1);
$pdf->SetFont("Arial","","10");
$pdf->MultiCell(0,4,$texto2);

$pdf->Ln(1);
$pdf->SetFont("Arial","B","9");
$pdf->Cell(0,5,"Da Instituição");
$pdf->Ln(1);
$pdf->SetFont("Arial","","10");
$pdf->MultiCell(0,4,$texto3);

$pdf->Ln(1);
$pdf->SetFont("Arial","B","9");
$pdf->Cell(0,5,"Do Supervisor");
$pdf->Ln(1);
$pdf->SetFont("Arial","","10");
$pdf->MultiCell(0,4,$texto4);

$pdf->Ln(1);
$pdf->SetFont("Arial","B","9");
$pdf->Cell(0,5,"Do Aluno");
$pdf->Ln(1);
$pdf->SetFont("Arial","","10");
$pdf->MultiCell(0,4,$texto5);

$final = "Rio de Janeiro, " . $dia . " do " . $mes . " de " . $ano . ".";

$pdf->Ln(5);
$pdf->Cell(0,5,$final,0,0,"R");

$pdf->Ln(10);
$pdf->Cell(0,5,"Coordenação de Estágio",0,0,"L");
$pdf->SetX(20);
$pdf->Cell(0,5,"Supervisor / CRESS: $cress",0,0,"C");
$pdf->SetX(30);
$pdf->Cell(0,5,"Aluno / DRE: $registro",0,0,"R");

$pdf->Output('/usr/local/htdocs/html/estagio/tmp/termo'.$registro.'.pdf');
$file = "/tmp/termo" .$registro . ".pdf";

echo "<html><head>
<meta http-equiv='refresh' content='2;url=../exibir/ver_cada.php?id_aluno=$id_aluno'>
<script>document.location='http://$servidor/estagio/$file';</script>
</head></html>";

?>