<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

// Cast all numeric inputs to int to prevent SQL injection
$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;

// Capturo a informacao sobre o aluno usando o modelo Aluno
$aluno = Aluno::find($aluno_id);
if (!$aluno) {
	$smarty = new Smarty_estagio;
	$smarty->assign("mensagem", "Não foi possível encontrar o aluno.");
	$smarty->display("erro.tpl");
	exit;
}

$aluno_id        = (int)$aluno->id;
$aluno_registro  = $aluno->registro;
$aluno_nome      = $aluno->nome;
$codigo_telefone = $aluno->codigo_telefone;
$aluno_telefone  = $aluno->telefone;
$codigo_celular  = $aluno->codigo_celular;
$aluno_celular   = $aluno->celular;
$aluno_email     = $aluno->email;
$cpf             = $aluno->cpf;
$identidade      = $aluno->identidade;
$nascimento      = (!empty($aluno->nascimento) && $aluno->nascimento !== '0000-00-00') ? date("d/m/Y", strtotime($aluno->nascimento)) : '';
$endereco        = $aluno->endereco;
$cep             = $aluno->cep;
$bairro          = $aluno->bairro;
$municipio       = $aluno->municipio;

// Tabela dos anteriores estagios usando modelo Aluno
$historico = Aluno::historicoEstagios($aluno_id);
$estagiarios = array();
foreach ($historico as $h) {
	$estagiarios[] = array(
		'id'              => $h['estagiario_id'],
		'periodo'         => $h['periodo'],
		'tc'              => $h['tc'],
		'nivel'           => $h['nivel'],
		'ch'              => $h['ch'],
		'nota'            => $h['nota'],
		'instituicao'     => $h['instituicao'],
		'instituicao_id'  => $h['instituicao_id'],
		'supervisor_id'   => $h['supervisor_id'],
		'supervisor_nome' => $h['supervisor'],
		'professor_id'    => $h['professor_id'],
		'professor_nome'  => $h['professor']
	);
}

// Capturo a informacao sobre as instituicoes usando modelo Instituicao
$todasInstituicoes = Instituicao::all("ORDER BY instituicao");
$instituicoes = array();
foreach ($todasInstituicoes as $inst) {
	$instituicoes[] = array(
		'instituicao_id' => (int)$inst->id,
		'instituicao'    => $inst->instituicao
	);
}

// Capturo a informacao sobre os supervisores usando modelo Supervisor
$todosSupervisores = Supervisor::all("ORDER BY nome");
$supervisores = array();
foreach ($todosSupervisores as $sup) {
	$supervisores[] = array(
		'supervisor_id' => (int)$sup->id,
		'supervisor'    => $sup->nome
	);
}

// Capturo a informacao sobre os professores usando modelo Professor
$todosProfessores = Professor::all("ORDER BY nome");
$professores = array();
foreach ($todosProfessores as $prof) {
	$professores[] = array(
		'professor_id' => (int)$prof->id,
		'professor'    => $prof->nome
	);
}

// Capturo a informacao sobre as areas usando modelo Area
$todasAreas = Area::all("ORDER BY area");
$areas = array();
foreach ($todasAreas as $ar) {
	$areas[] = array(
		'id_area' => (int)$ar->id,
		'area'    => $ar->area
	);
}

$smarty = new Smarty_estagio;

// Tabela de estagios anteriores
$smarty->assign("estagiarios", $estagiarios);

// Tabela modificar dados do aluno
$smarty->assign("aluno_id", $aluno_id);
$smarty->assign("registro", $aluno_registro);
$smarty->assign("aluno_nome", $aluno_nome);
$smarty->assign("codigo_telefone", $codigo_telefone);
$smarty->assign("telefone", $aluno_telefone);
$smarty->assign("codigo_celular", $codigo_celular);
$smarty->assign("celular", $aluno_celular);
$smarty->assign("email", $aluno_email);
$smarty->assign("cpf", $cpf);
$smarty->assign("identidade", $identidade);
$smarty->assign("nascimento", $nascimento);
$smarty->assign("endereco", $endereco);
$smarty->assign("cep", $cep);
$smarty->assign("bairro", $bairro);
$smarty->assign("municipio", $municipio);

// Seletores para inserir novo estagio
$smarty->assign("instituicoes", $instituicoes);
$smarty->assign("supervisores", $supervisores);
$smarty->assign("professores", $professores);
$smarty->assign("areas", $areas);

$smarty->display("alunos-atualizar_modificar_estagio.tpl");

exit;

?>