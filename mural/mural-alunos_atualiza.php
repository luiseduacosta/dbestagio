<?php
/**
 * mural-alunos_atualiza.php — Gravação da ficha do candidato no mural.
 *
 * Recebe o formulário de mural-alunos_modifica.tpl: atualiza os dados do aluno
 * e, quando vem uma oferta (muralestagio_id), registra a inscrição dele na
 * oferta do período atual, redirecionando para a lista de inscritos.
 *
 * Exige usuário logado (autentica.inc). Antes o campo "sistema_autentica" do
 * formulário — controlado pelo cliente — decidia se os dados do aluno seriam
 * gravados, e o script era público: qualquer visitante podia alterar a ficha de
 * qualquer aluno. Agora quem decide é a sessão autenticada.
 *
 * Toda a leitura/gravação passa pelos modelos (libphp/Aluno.php e
 * libphp/Inscricao.php), que gravam com parâmetros ligados. O SQL anterior era
 * montado por interpolação de strings, o que quebrava a gravação de qualquer
 * nome com apóstrofo (ex.: "Isabelle Motta D' Assumpção"). O DDD do celular
 * também vinha de um campo com nome errado ('codigo_celuar'): era sempre
 * gravado 21, ignorando o que o formulário enviava.
 */

include_once(__DIR__ . "/../autentica.inc");
require_once(__DIR__ . "/../libphp/models.php");

$registro        = isset($_REQUEST['registro']) ? trim((string)$_REQUEST['registro']) : '';
$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;

if (!ctype_digit($registro)) {
	die("Registro (DRE) inválido: informe somente números.");
}

// A ficha só existe para aluno já cadastrado.
$aluno = Aluno::firstWhere('registro', (int)$registro);
if ($aluno === null) {
	die("Não foi possível encontrar o aluno com registro $registro na tabela alunos");
}
$aluno_id = (int)$aluno->id;

// Atualiza a ficha do aluno com o que veio do formulário.
$nascimento = isset($_POST['nascimento']) ? trim($_POST['nascimento']) : '';

$aluno->nome            = isset($_POST['nome']) ? $_POST['nome'] : '';
$aluno->codigo_telefone = (isset($_POST['codigo_telefone']) && $_POST['codigo_telefone'] !== '') ? (int)$_POST['codigo_telefone'] : 21;
$aluno->telefone        = isset($_POST['telefone']) ? $_POST['telefone'] : '';
$aluno->codigo_celular  = (isset($_POST['codigo_celular']) && $_POST['codigo_celular'] !== '') ? (int)$_POST['codigo_celular'] : 21;
$aluno->celular         = isset($_POST['celular']) ? $_POST['celular'] : '';
$aluno->email           = strtolower(isset($_POST['email']) ? $_POST['email'] : '');
$aluno->cpf             = isset($_POST['cpf']) ? $_POST['cpf'] : '';
$aluno->identidade      = isset($_POST['identidade']) ? $_POST['identidade'] : '';
$aluno->orgao           = isset($_POST['orgao']) ? $_POST['orgao'] : '';
$aluno->nascimento      = ($nascimento === '') ? null : date("Y-m-d", strtotime($nascimento));
$aluno->endereco        = isset($_POST['endereco']) ? $_POST['endereco'] : '';
$aluno->cep             = isset($_POST['cep']) ? $_POST['cep'] : '';
$aluno->municipio       = isset($_POST['municipio']) ? $_POST['municipio'] : '';
$aluno->bairro          = isset($_POST['bairro']) ? $_POST['bairro'] : '';

if (!$aluno->save()) {
	error_log("Erro ao atualizar aluno: " . ADODB_Model::$db->ErrorMsg());
	die("Não foi possível atualizar a tabela alunos");
}

// Insere a inscrição para seleção de estágio, quando há oferta escolhida.
if ($muralestagio_id > 0) {
	$periodo = PERIODO_ATUAL;

	// O aluno já fez inscrição nesta seleção? Segue direto para a lista.
	if (Inscricao::duplicada($registro, $muralestagio_id, $periodo) !== null) {
		header("Location:listaInscritos.php?muralestagio_id=$muralestagio_id");
		exit;
	}

	$nova = new Inscricao();
	$nova->registro        = (int)$registro;
	$nova->muralestagio_id = $muralestagio_id;
	$nova->data            = date("Y-m-d");
	$nova->periodo         = $periodo;
	$nova->aluno_id        = $aluno_id;

	if (!$nova->save()) {
		error_log("Erro ao inserir inscricao: " . ADODB_Model::$db->ErrorMsg());
		die("Não foi possível inserir o registro na tabela inscricoes");
	}

	header("Location:listaInscritos.php?muralestagio_id=$muralestagio_id");
	exit;
}

header("Location:../alunos/ver_cada.php?registro={$registro}");

exit;

?>
