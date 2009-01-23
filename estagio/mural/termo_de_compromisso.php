<?php

$dia = date("d");
$mes = date("m");
$ano = date("Y");

$texto = "
UNIVERSIDADE FEDERAL DO RIO DE JANEIRO - UFRJ
ESCOLA DE SERVI�O SOCIAL -ESS
TERMO DE COMPROMISSO
O presente TERMO DE COMPROMISSO DE EST�GIO que entre si assinam Coordenação de Estágio da Escola de Serviço Social/UFRJ/Estudante " . $nome .", instituição ". $instituicao . " e Supervisor(a) AS. ". $supervisor .", visa estabelecer condições gerais que regulam a realização de ESTAGIO CURRICULAR. Atividade obrigatória para a conclusão da Graduação em Serviço Social. Ficam estabelecidas entre as partes as seguintes condições básicas para a realização do estágio:
Das Partes
Art. 01. As atividades a serem desenvolvidas pelo estagiário, deverão ser compatíveis com o curso de Serviço Social, envolvem observação, estudos, elaboração de projetos e realização de leituras e atividades práticas.
Art. 02. A permanência em cada campo de estágio deverá ser de no mínimo dois semestres letivos consecutivos. A quebra deste contrato, deverá ser precedida de apresentação de solicitação formal à Coordenação de Estágio, com no mínimo 1 mês de antes do término do período letivo em curso. Contendo parecer da supervisora e do professor de OTP.
Art. 03. Em caso de demissão do supervisor, ou a ocorrência de férias deste profissional ao longo do período letivo, outro assistente social deverá ser imediatamente indicado para supervisão técnica do estagiário.
Da ESS
Art. 04. De acordo com a orientação geral da Universidade do Rio de Janeiro, no que concerne à estágios, e o currículo da Escola de Serviço Social, implantado em 2001. O estágio será realizado por um período de, no mínimo 120 horas/semestre, não podendo ultrapassar 20h semanais.
Art. 05. Será indicado pelos Departamentos da ESS, um professor para acompanhamento acadêmico referente a área temática da instituição que o aluno realizará o seu estágio
Art. 06. A Escola de Serviço Social fornecerá à Instituição informações e declarações solicitadas, consideradas necessárias ao bom andamento do estágio curricular.
Da Instituição
Art. 07. O estágio será realizado no âmbito da unidade concedente onde deve existir um Assistente Social responsável pelo projeto desenvolvido pelo Serviço Social. As atividades de estágio serão realizadas em horário compatível com as atividades escolares do estagiário e com as normas vigentes no âmbito da unidade concedente.
Art. 08. A Coordenação de Estágio/ESS deve ser informada com prazo de 01 (um) mês de antecedência o afastamento do supervisor do campo de estágio e a indicação do seu substituto.
Do Supervisor
Art. 09. É de responsabilidade do Assistente Social supervisor o acompanhamento e supervisão sistemática do processo vivenciado pelo aluno durante o período de estágio.
Art. 10. No final de cada mês o supervisor atestará á unidade de ensino, em formulário próprio, a carga horária cumprida pelo estagiário.
Art. 11. No final de cada período letivo o supervisor encaminhará, ao professor da disciplina de Orientação e Treinamento Profissional, avaliação do processo vivenciado pelo aluno durante o semestre. Instrumento este utilizado pelo professor na avaliação final do aluno.
Do Aluno
Art. 12. Cabe ao estagiário cumprir o horário acordado com a unidade para o desempenho das atividades definidas no Plano de Estágio, observando os princípios éticos que rege o Serviço Social. São considerados motivos justos ao não cumprimento da programação, as obrigações escolares do estagiário que devem ser comunicadas, ao supervisor, em tempo hábil.
Art. 13. 0 aluno se compromete a cuidar e manter sigilo em relação à documentação, da unidade campo de estágio, mesmo após o seu desligamento.
Art. 14. O aluno deverá cumprir com responsabilidade e assiduidade os compromisso assumidos junto ao acampo de estágio, independente do calendário e férias acadêmicas.
Art. 15. O período de permanência do aluno no campo de estágio se dará de acordo com o contrato formal ou informal assumido com o supervisor
Art. 16. O presente Termo de Compromisso terá validade de 04/08/2008 a 06/12/2008, correspondente ao Estagio ".$nivel.". Sua interrupção antes do período previsto, acarretará prejuízo para o aluno na sua avaliação acadêmica.
Art. 17. Os casos omissos serão encaminhados à Coordenação de Estágio para serem dirimidos.

Rio de Janeiro, ". $dia ." de ". $mes ." de ". $ano. ".

Coordenação de Estágio Supervisor (N° do CRESS) Aluno
";

define("FPDF_FONTPATH","/usr/local/htdocs/html/fpdf151/font/");
define("FPDF","/usr/local/htdocs/html/fpdf151/");

require(FPDF."fpdf.php");

$pdf = new FPDF();
$pdf->Open();

$pdf->AddPage();

$pdf->SetFont("Arial","","8");
$pdf->MultiCell(0,5,$texto);

$pdf->Output();
