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
	$classe = "renova��o"; // Nao muda e nao tinha que mudar
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

$cabecalho2 = $pdf->GetStringWidth("Escola de Servi�o Social");
$pdf->SetX((210-$cabecalho2)/2);
$pdf->Cell($cabecalho2,3,"Escola de Servi�o Social",0,1,'C',0);
$pdf->Ln(2);

$cabecalho3 = $pdf->GetStringWidth("Coordena��o de Est�gio");
$pdf->SetX((210-$cabecalho3)/2);
$pdf->Cell($cabecalho3,3,"Coordena��o de Est�gio",0,1,'C',0);
$pdf->Ln(2);

$cabecalho3 = $pdf->GetStringWidth("Termo de compromisso (estagio:  )");
$pdf->SetX((210-$cabecalho3)/2);
$pdf->Cell($cabecalho3,3,"Termo de Compromisso (Est�gio $nivel_romano $classe)",0,1,'C',0);
$pdf->Ln(2);

$texto0 = "
O presente TERMO DE COMPROMISSO DE EST�GIO que entre si assinam Coordena��o de Est�gio da Escola de Servi�o Social/UFRJ/Estudante " . strtoupper($nome) . ", institui��o ". $instituicao . " e Supervisor(a) AS. ". strtoupper($supervisor) . ", visa estabelecer condi��es gerais que regulam a realiza��o de ESTAGIO CURRICULAR. Atividade obrigat�ria para a conclus�o da Gradua��o em Servi�o Social. Ficam estabelecidas entre as partes as seguintes condi��es b�sicas para a realiza��o do est�gio:
";

$texto1 = "
Art. 01. As atividades a serem desenvolvidas pelo estagi�rio, dever�o ser compat�veis com o curso de Servi�o Social, envolvem observa��o, estudos, elabora��o de projetos e realiza��o de leituras e atividades pr�ticas.
Art. 02. A perman�ncia em cada campo de est�gio dever� ser de no m�nimo dois semestres letivos consecutivos. A quebra deste contrato, dever� ser precedida de apresenta��o de solicita��o formal � Coordena��o de Est�gio, com no m�nimo 1 m�s de antes do t�rmino do per�odo letivo em curso. Contendo parecer da supervisora e do professor de OTP.
Art. 03. Em caso de demiss�o do supervisor, ou a ocorr�ncia de f�rias deste profissional ao longo do per�odo letivo, outro assistente social dever� ser imediatamente indicado para supervis�o t�cnica do estagi�rio.
";

$texto2 = "
Art. 04. De acordo com a orienta��o geral da Universidade do Rio de Janeiro, no que concerne � est�gios, e o curr�culo da Escola de Servi�o Social, implantado em 2001. O est�gio ser� realizado por um per�odo de, no m�nimo 120 horas/semestre, n�o podendo ultrapassar 20h semanais.
Art. 05. Ser� indicado pelos Departamentos da ESS, um professor para acompanhamento acad�mico referente a �rea tem�tica da institui��o que o aluno realizar� o seu est�gio.
Art. 06. A Escola de Servi�o Social fornecer� � Institui��o informa��es e declara��es solicitadas, consideradas necess�rias ao bom andamento do est�gio curricular.
";

$texto3 = "
Art. 07. O est�gio ser� realizado no �mbito da unidade concedente onde deve existir um Assistente Social respons�vel pelo projeto desenvolvido pelo Servi�o Social. As atividades de est�gio ser�o realizadas em hor�rio compat�vel com as atividades escolares do estagi�rio e com as normas vigentes no �mbito da unidade concedente.
Art. 08. A Coordena��o de Est�gio/ESS deve ser informada com prazo de 01 (um) m�s de anteced�ncia o afastamento do supervisor do campo de est�gio e a indica��o do seu substituto.
";

$texto4 = "
Art. 09. � de responsabilidade do Assistente Social supervisor o acompanhamento e supervis�o sistem�tica do processo vivenciado pelo aluno durante o per�odo de est�gio.
Art. 10. No final de cada m�s o supervisor atestar� � unidade de ensino, em formul�rio pr�prio, a carga hor�ria cumprida pelo estagi�rio.
Art. 11. No final de cada per�odo letivo o supervisor encaminhar�, ao professor da disciplina de Orienta��o e Treinamento Profissional, avalia��o do processo vivenciado pelo aluno durante o semestre. Instrumento este utilizado pelo professor na avalia��o final do aluno.
";

$texto5 = "
Art. 12. Cabe ao estagi�rio cumprir o hor�rio acordado com a unidade para o desempenho das atividades definidas no Plano de Est�gio, observando os princ�pios �ticos que rege o Servi�o Social. S�o considerados motivos justos ao n�o cumprimento da programa��o, as obriga��es escolares do estagi�rio que devem ser comunicadas, ao supervisor, em tempo h�bil.
Art. 13. 0 aluno se compromete a cuidar e manter sigilo em rela��o � documenta��o, da unidade campo de est�gio, mesmo ap�s o seu desligamento.
Art. 14. O aluno dever� cumprir com responsabilidade e assiduidade os compromisso assumidos junto ao acampo de est�gio, independente do calend�rio e f�rias acad�micas.
Art. 15. O per�odo de perman�ncia do aluno no campo de est�gio se dar� de acordo com o contrato formal ou informal assumido com o supervisor.
Art. 16. O presente Termo de Compromisso ter� validade de $validade1 a $validade2, correspondente ao Estagio ". $nivel_romano .". Sua interrup��o antes do per�odo previsto, acarretar� preju�zo para o aluno na sua avalia��o acad�mica.
Art. 17. Os casos omissos ser�o encaminhados � Coordena��o de Est�gio para serem dirimidos.
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
$pdf->Cell(0,5,"Da Institui��o");
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
$pdf->Cell(0,5,"Coordena��o de Est�gio",0,0,"L");
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