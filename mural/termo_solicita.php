<?php
include_once("../autentica.inc");
require_once(__DIR__ . "/../libphp/models.php");

// Alunos
$aluno_id        = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;
$registro        = isset($_REQUEST['registro']) ? $_REQUEST['registro'] : '';

// Apenas aluno e admin solicitam termo de compromisso (mesma regra do handler
// alunos/atualizar/atualiza_termo.php). O aluno fica restrito ao proprio DRE.
if ($isAluno) {
	$aluno_logado = Aluno::find($usuario_aluno_id);
	if (!$aluno_logado) die ("Nao foi possivel encontrar o seu registro");
	$registro = $aluno_logado->registro;
	$aluno_id = (int)$aluno_logado->id;
} elseif (!$isAdmin) {
	die ("Acesso restrito: apenas alunos e administradores podem solicitar o termo de compromisso.");
}
$nome            = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '';
$codigo_telefone = isset($_REQUEST['codigo_telefone']) ? $_REQUEST['codigo_telefone'] : '';
$telefone        = isset($_REQUEST['telefone']) ? $_REQUEST['telefone'] : '';
$codigo_celular  = isset($_REQUEST['codigo_celular']) ? $_REQUEST['codigo_celular'] : '';
$celular         = isset($_REQUEST['celular']) ? $_REQUEST['celular'] : '';
$email           = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$identidade      = isset($_REQUEST['identidade']) ? $_REQUEST['identidade'] : '';
$orgao           = isset($_REQUEST['orgao']) ? $_REQUEST['orgao'] : '';
$cpf             = isset($_REQUEST['cpf']) ? $_REQUEST['cpf'] : '';
$nascimento      = isset($_REQUEST['nascimento']) ? $_REQUEST['nascimento'] : '';
$endereco        = isset($_REQUEST['endereco']) ? $_REQUEST['endereco'] : '';
$cep             = isset($_REQUEST['cep']) ? $_REQUEST['cep'] : '';
$bairro          = isset($_REQUEST['bairro']) ? $_REQUEST['bairro'] : '';
$municipio       = isset($_REQUEST['municipio']) ? $_REQUEST['municipio'] : '';
$observacoes     = isset($_REQUEST['observacoes']) ? $_REQUEST['observacoes'] : '';
$nivel           = isset($_REQUEST['nivel']) ? $_REQUEST['nivel'] : '';
$instituicao_id  = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0;
$supervisor_id   = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
// echo $nascimento . "<br>";

// Aluno
$sql  = "select registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, ";
$sql .= "endereco, cep, bairro, municipio, observacoes from alunos where registro=?";

$resultado = Aluno::query($sql, array($registro));
// Verifico se o aluno está cadastrado
$quantidade = count($resultado);
$aluno_novo = 0;

if ($quantidade == 0) {
	echo "Aluno não cadastrado em estágio <br />";
	echo "Causa provável: aluno não participou de seleção de estágio. <br />";
	echo "Favor enviar um e-mail para <a href='mailto:estagio@ess.ufrj.br'>estagio@ess.ufrj.br</a> informando o seu DRE.";
	exit;
}

foreach ($resultado as $item) {
	// $id_aluno = $resultado->fields['id'];
	$registro = $item->registro;
	$nome = $item->nome;
	$codigo_telefone = $item->codigo_telefone;
	$telefone = $item->telefone;
	$codigo_celular = $item->codigo_celular;
	$celular = $item->celular;
	$email = $item->email;
	$cpf = $item->cpf;
	$identidade = $item->identidade;
	$orgao = $item->orgao;
	$nascimento = $item->nascimento;
	// Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa ('' se nula)
	$data_sql = estagio_formata_data($nascimento, 'd/m/Y');

	$endereco = $item->endereco;
	$cep = $item->cep;
	$bairro = $item->bairro;
	$municipio = $item->municipio;
	$observacoes = $item->observacoes;
	$aluno_novo = 1; // Aluno estagiando
	// echo $observacoes . "<br>";
}

// Situacao do aluno no periodo atual do termo de compromisso:
// 0 = nunca estagiou, 1 = estagia no periodo atual, 2 = estagia em outro periodo
$estagios_aluno = Estagiario::estagiosPorRegistroDesc($registro);
$aluno = 0;
if (!empty($estagios_aluno)) {
	$aluno = 2;
	foreach ($estagios_aluno as $estagio) {
		if ($estagio['periodo'] == TC_PERIODO_ATUAL) {
			$aluno = 1;
			break;
		}
	}
}

// Nivel do formulario: o informado na requisicao ou o proximo nivel do aluno
$nivel_formulario = (int)$nivel;
if ($nivel_formulario < 1 || $nivel_formulario > 4) {
	$nivel_formulario = 1;
	if (!empty($estagios_aluno)) {
		$nivel_anterior = (int)$estagios_aluno[0]['nivel'];
		$nivel_formulario = ($estagios_aluno[0]['periodo'] == TC_PERIODO_ATUAL)
			? max(1, $nivel_anterior)
			: (($nivel_anterior >= 4) ? 4 : $nivel_anterior + 1);
	}
}
$nivel = $nivel_formulario;
$niveis_romanos = array(1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV');
$nivel_romano = isset($niveis_romanos[$nivel_formulario]) ? $niveis_romanos[$nivel_formulario] : 'I';

// Instituicao e supervisor do estagio do aluno, para pre-selecionar no formulario
$instituicao_id = 0;
$instituicao = '';
$supervisor_id = 0;
$supervisor = '';
$instituicao_atual_id = 0;
$instituicao_anterior_id = 0;
if (!empty($estagios_aluno)) {
	$instituicao_id = (int)$estagios_aluno[0]['instituicao_id'];
	$instituicao    = (string)$estagios_aluno[0]['instituicao'];
	$supervisor_id  = (int)$estagios_aluno[0]['supervisor_id'];
	$supervisor     = (string)$estagios_aluno[0]['supervisor_nome'];
	foreach ($estagios_aluno as $estagio) {
		if ($estagio['periodo'] == TC_PERIODO_ATUAL) {
			$instituicao_atual_id = (int)$estagio['instituicao_id'];
		} elseif ($instituicao_anterior_id == 0) {
			$instituicao_anterior_id = (int)$estagio['instituicao_id'];
		}
	}
}

// Capturo as instituicoes
$sql_instituicoes = "select id, instituicao from instituicoes order by instituicao";
$resposta_instituicoes = Instituicao::query($sql_instituicoes);
if ($resposta_instituicoes === false) die ("Nao foi possivel consultar a tabela instituicoes");
$i = 1;
$instituicoes = array();
foreach ($resposta_instituicoes as $item) {
    $instituicoes[$i]['id'] = (int)$item->id;
    $instituicoes[$i]['instituicao_id'] = (int)$item->id;
    $instituicoes[$i]['instituicao'] = $item->instituicao;
    $i++;
}

// Capturo os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$resposta_supervisores = Supervisor::query($sql_supervisores);
if ($resposta_supervisores === false) die ("Nao foi possivel consultar a tabela supervisores");
$i = 1;
$supervisores = array();
foreach ($resposta_supervisores as $item) {
    $supervisores[$i]['id']            = (int)$item->id;
    $supervisores[$i]['supervisor_id'] = (int)$item->id;
    $supervisores[$i]['nome']          = $item->nome;
    $i++;
}

$smarty = new Smarty_estagio;
// Aluno
$smarty->assign("aluno_id",$aluno_id);
$smarty->assign("registro",$registro);
$smarty->assign("aluno_nome",$nome);
$smarty->assign("codigo_telefone",$codigo_telefone);
$smarty->assign("telefone",$telefone);
$smarty->assign("codigo_celular",$codigo_celular);
$smarty->assign("celular",$celular);
$smarty->assign("email",$email);
$smarty->assign("cpf",$cpf);
$smarty->assign("identidade",$identidade);
$smarty->assign("orgao",$orgao);
$smarty->assign("nascimento",$data_sql);
$smarty->assign("endereco",$endereco);
$smarty->assign("cep",$cep);
$smarty->assign("bairro",$bairro);
$smarty->assign("municipio",$municipio);
$smarty->assign("observacoes",$observacoes);
$smarty->assign("aluno_novo",$aluno_novo);
// Situacao e nivel do aluno
$smarty->assign("aluno",$aluno);
$smarty->assign("nivel",$nivel);
$smarty->assign("nivel_romano",$nivel_romano);
// Instituicao e supervisor pre-selecionados
$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("supervisor_id",$supervisor_id);
$smarty->assign("supervisor",$supervisor);
$smarty->assign("instituicao_atual_id",$instituicao_atual_id);
$smarty->assign("instituicao_anterior_id",$instituicao_anterior_id);
// Destino do formulario (este script roda em mural/, o handler fica em alunos/atualizar/)
$smarty->assign("form_action","../alunos/atualizar/atualiza_termo.php");
// Instituicoes
$smarty->assign("instituicoes",$instituicoes);
// Supervisores
$smarty->assign("supervisores",$supervisores);

$smarty->display("alunos-atualizar_atualiza_termo.tpl");
// $smarty->display("muralestagios-termo_solicita.tpl");

exit;

?>