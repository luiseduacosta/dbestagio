<?php
/**
 * cadastro.php — Cadastro público do candidato novo no mural de estágios.
 *
 * Mantém a rota pública do mural (POST de alunos-mural_insere.tpl, alcançada
 * por verifica.php): grava o aluno novo na tabela alunos e a inscrição dele na
 * oferta de mural_estagios escolhida, e redireciona para a lista de inscritos.
 *
 * Toda a leitura/gravação passa pelos modelos (libphp/Aluno.php e
 * libphp/Inscricao.php), que gravam com parâmetros ligados — sem SQL cru.
 * Observe que a coluna alunos.id NÃO é auto_increment: o id é gerado por
 * Aluno::proximoId().
 */

include_once(__DIR__ . "/../setup.php");
require_once(__DIR__ . "/../libphp/models.php");

$submit = isset($_POST['submit']) ? $_POST['submit'] : null;

$registro        = isset($_REQUEST['registro']) ? trim((string)$_REQUEST['registro']) : '';
$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;

// Instituição da oferta em que o candidato se inscreve (consulta parametrizada).
$oferta = ($muralestagio_id > 0) ? Mural::find($muralestagio_id) : null;
if ($oferta === null) {
	die("Oferta de estágio não encontrada no mural (id $muralestagio_id).");
}
$instituicao = $oferta->instituicao;

if ($submit) {

	if (!ctype_digit($registro)) {
		die("Registro (DRE) inválido: informe somente números.");
	}

	// Aluno já cadastrado: segue para a atualização dos dados (mesma rota antiga).
	if (Aluno::registroDuplicado($registro) !== null) {
		header("Location:mural-alunos_modifica.php?registro=$registro");
		exit;
	}

	$nascimento = isset($_POST['nascimento']) ? trim($_POST['nascimento']) : '';
	$dataNascimento = ($nascimento === '') ? null : date("Y-m-d", strtotime($nascimento));

	// Grava o aluno novo.
	$novoAluno = new Aluno();
	$novoAluno->id                = Aluno::proximoId(); // id não é auto_increment.
	$novoAluno->registro          = (int)$registro;
	$novoAluno->nome              = isset($_POST['nome']) ? $_POST['nome'] : '';
	$novoAluno->codigo_telefone   = (isset($_POST['codigo_telefone']) && $_POST['codigo_telefone'] !== '') ? (int)$_POST['codigo_telefone'] : 21;
	$novoAluno->telefone          = isset($_POST['telefone']) ? $_POST['telefone'] : '';
	$novoAluno->codigo_celular    = (isset($_POST['codigo_celular']) && $_POST['codigo_celular'] !== '') ? (int)$_POST['codigo_celular'] : 21;
	$novoAluno->celular           = isset($_POST['celular']) ? $_POST['celular'] : '';
	$novoAluno->email             = strtolower(isset($_POST['email']) ? $_POST['email'] : '');
	$novoAluno->cpf               = isset($_POST['cpf']) ? $_POST['cpf'] : '';
	$novoAluno->identidade        = isset($_POST['identidade']) ? $_POST['identidade'] : '';
	$novoAluno->orgao             = isset($_POST['orgao']) ? $_POST['orgao'] : '';
	$novoAluno->nascimento        = $dataNascimento;
	$novoAluno->endereco          = isset($_POST['endereco']) ? $_POST['endereco'] : '';
	$novoAluno->cep               = isset($_POST['cep']) ? $_POST['cep'] : '';
	$novoAluno->municipio         = isset($_POST['municipio']) ? $_POST['municipio'] : '';
	$novoAluno->bairro            = isset($_POST['bairro']) ? $_POST['bairro'] : '';
	$novoAluno->estagiarios_count = 0;
	$novoAluno->inscricao_count   = 0;

	if (!$novoAluno->save()) {
		error_log("Erro ao inserir aluno: " . ADODB_Model::$db->ErrorMsg());
		die("Não foi possível inserir o registro na tabela alunos");
	}

	// Grava a inscrição do aluno novo na oferta escolhida.
	$novaInscricao = new Inscricao();
	$novaInscricao->registro        = (int)$registro;
	$novaInscricao->muralestagio_id = $muralestagio_id;
	$novaInscricao->aluno_id        = $novoAluno->id;
	$novaInscricao->periodo         = PERIODO_ATUAL;
	$novaInscricao->data            = date('Y-m-d');

	if (!$novaInscricao->save()) {
		error_log("Erro ao inserir inscricao: " . ADODB_Model::$db->ErrorMsg());
		die("Não foi possível inserir o registro na tabela inscricoes");
	}

	header("Location:listaInscritos.php?muralestagio_id=$muralestagio_id");
	exit;
}

$smarty = new Smarty_estagio;
$smarty->assign("registro",$registro);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("muralestagio_id",$muralestagio_id);
$smarty->display("../../mural/alunos-mural_insere.tpl");

exit;

?>
