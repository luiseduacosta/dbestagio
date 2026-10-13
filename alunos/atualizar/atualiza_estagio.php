<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php"); // Inicializa os modelos

$origem = isset($_REQUEST['origem']) ? $_REQUEST['origem'] : '';
if (empty($origem )) {
	$origem = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''; // Para poder retornar desde onde foi chamado
}
// echo "Origem: " . $origem . "<br>";

$estagiario_id = isset($_REQUEST['estagiario_id']) ? $_REQUEST['estagiario_id'] : NULL;
$aluno_id = isset($_REQUEST['aluno_id']) ? $_REQUEST['aluno_id'] : NULL;

// Aluno - Usando modelo Aluno
$aluno = Aluno::find($aluno_id);
if (!$aluno) die ("Nao foi possivel consultar a tabela alunos");
$nome_aluno = $aluno->nome;
$registro   = $aluno->registro;

// Estagiarios - Usando modelo Estagiario
$estagiario = Estagiario::find($estagiario_id);
if (!$estagiario) die ("Nao foi possivel consultar a tabela estagiarios");
$periodo        = $estagiario->periodo;
$tc	        	= $estagiario->tc;
$nivel          = $estagiario->nivel;
$instituicao_id = $estagiario->instituicao_id;
$supervisor_id  = $estagiario->supervisor_id;
$professor_id   = $estagiario->professor_id;
$nota           = $estagiario->nota;
$ch             = $estagiario->ch;

// Nome da Instituicao - Usando modelo Instituicao
if (!empty($instituicao_id)) {
	$instituicaoObj = Instituicao::find($instituicao_id);
	if (!$instituicaoObj) die ("Nao foi possivel consultar a tabela instituicoes");
	$instituicao = $instituicaoObj->instituicao;
} else {
	$instituicao_id = 0;
	$instituicao = "Sem dados";
}

// Nome do Supervisor - Usando modelo Supervisor
if (!empty($supervisor_id)) {
	$supervisorObj = Supervisor::find($supervisor_id);
	if (!$supervisorObj) die ("Nao foi possivel consultar a tabela supervisores");
	$supervisor = $supervisorObj->nome;
} else {
	$supervisor_id = 0;
	$supervisor = "Sem dados";
}

// Nome do Professor - Usando modelo Professor
if (!empty($professor_id)) {
	$professorObj = Professor::find($professor_id);
	if (!$professorObj) die ("Nao foi possivel consultar a tabela professores");
	$professor = $professorObj->nome;
} else {
	$professor_id = 0;
	$professor = "Sem dados";
}

// Capturo a informacao sobre as instituicoes - Usando modelo Instituicao
$todasInstituicoes = Instituicao::all();
$i = 0;
$instituicoes[$i]['instituicao_id'] = "0";
$instituicoes[$i]['instituicao'] = "Sem dados";
$i++;
foreach ($todasInstituicoes as $inst) {
	$instituicoes[$i]['instituicao_id'] = $inst->id;
	$instituicoes[$i]['instituicao']    = $inst->instituicao;
	$i++;
}

// Capturo a informacao sobre os supervisores - Usando modelo Supervisor
$todosSupervisores = Supervisor::all();
$i = 0;
$supervisores[$i]['supervisor_id'] = "0";
$supervisores[$i]['supervisor'] = "Sem dados";
$i++;
foreach ($todosSupervisores as $sup) {
	$supervisores[$i]['supervisor_id'] = $sup->id;
	$supervisores[$i]['supervisor']    = $sup->nome;
	$i++;
}

// Capturo a informacao sobre os professores - Usando modelo Professor
$todosProfessores = Professor::all();
if (empty($todosProfessores)) die ("Nao foi possivel consultar a tabela professores");
$i = 0;
$professores[$i]['professor_id'] = "0";
$professores[$i]['professor'] = "Sem dados";
$i++;
foreach ($todosProfessores as $prof) {
	$professores[$i]['professor_id'] = $prof->id;
	$professores[$i]['professor']    = $prof->nome;
	$i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("atualizar_estagio",1);
$smarty->assign("origem",$origem);
$smarty->assign("aluno_id",$aluno_id);
$smarty->assign("nome_aluno",$nome_aluno);
$smarty->assign("registro",$registro);
$smarty->assign("periodo",$periodo);
$smarty->assign("tc",$tc);
$smarty->assign("nivel",$nivel);
$smarty->assign("estagiario_id",$estagiario_id);
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("nome_instituicao",$instituicao);
$smarty->assign("supervisor_id",$supervisor_id);
$smarty->assign("nome_supervisor",$supervisor);
$smarty->assign("professor_id",$professor_id);
$smarty->assign("nome_professor",$professor);
$smarty->assign("nota",$nota);
$smarty->assign("ch",$ch);
$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("supervisores",$supervisores);
$smarty->assign("professores",$professores);

$smarty->display("alunos-atualizar_atualiza_estagio.tpl");

?>