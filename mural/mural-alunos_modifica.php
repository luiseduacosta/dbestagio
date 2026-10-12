<?php
/**
 * mural-alunos_modifica.php — Ficha do candidato já cadastrado no mural.
 *
 * Mostra a instituição da oferta, o histórico de estágios do aluno (quando
 * aluno=1) e o formulário com os dados dele; a confirmação segue para
 * mural-alunos_atualiza.php. Acesso restrito a usuário logado (autentica.inc).
 *
 * As consultas passam pelos modelos (libphp/Mural.php e libphp/Aluno.php), sem
 * SQL cru. O histórico é buscado pelo registro: há linhas de estagiarios com
 * aluno_id nulo, que a busca por aluno_id perderia.
 */

include_once(__DIR__ . "/../autentica.inc");
require_once(__DIR__ . "/../libphp/models.php");

$registro        = isset($_REQUEST['registro']) ? (int)$_REQUEST['registro'] : 0;
$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;

// Instituição da oferta (consulta parametrizada).
$oferta = Mural::find($muralestagio_id);
$instituicao = ($oferta !== null) ? $oferta->instituicao : '';

// Dados do aluno.
$aluno = Aluno::porRegistro($registro);
if ($aluno === null) {
	die("Não foi possível encontrar o aluno com registro $registro na tabela alunos");
}
$aluno_id = (int)$aluno['id'];

// Histórico de estágios anteriores.
$estagiarios = array();
foreach (Aluno::historicoEstagiosPorRegistro($registro) as $item) {
	$estagiarios[] = array(
		'id'          => $item['estagiario_id'],
		'periodo'     => $item['periodo'],
		'nivel'       => $item['nivel'],
		'turno'       => '', // a tabela estagiarios não tem coluna turno
		'instituicao' => $item['instituicao'],
		'supervisor'  => $item['supervisor'],
	);
}

// Data de nascimento no formato do formulário (dd-mm-aaaa).
$nascimento = $aluno['nascimento'];
$aluno_nascimento = empty($nascimento) ? '' : date("d-m-Y", strtotime($nascimento));

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica", $sistema_autentica);
// O histórico só aparece para aluno já conhecido (aluno=1, enviado por verifica.php).
$smarty->assign("aluno", isset($_REQUEST['aluno']) ? (int)$_REQUEST['aluno'] : (!empty($estagiarios) ? 1 : 0));
$smarty->assign("aluno_id", $aluno_id);
// Tabela de estagios anteriores
$smarty->assign("estagiarios", $estagiarios);
// Tabela inserir novo estágio
$smarty->assign("registro", $registro);
$smarty->assign("aluno_nome", $aluno['nome']);
$smarty->assign("codigo_telefone", $aluno['codigo_telefone']);
$smarty->assign("telefone", $aluno['telefone']);
$smarty->assign("codigo_celular", $aluno['codigo_celular']);
$smarty->assign("celular", $aluno['celular']);
$smarty->assign("email", strtolower((string)$aluno['email']));
$smarty->assign("cpf", $aluno['cpf']);
$smarty->assign("identidade", $aluno['identidade']);
$smarty->assign("orgao", $aluno['orgao']);
$smarty->assign("nascimento", $aluno_nascimento);
$smarty->assign("endereco", $aluno['endereco']);
$smarty->assign("cep", $aluno['cep']);
$smarty->assign("municipio", $aluno['municipio']);
$smarty->assign("bairro", $aluno['bairro']);
$smarty->assign("observacoes", $aluno['observacoes']);

$smarty->assign("instituicao", $instituicao);
$smarty->assign("muralestagio_id", $muralestagio_id);

$smarty->display("../../mural/mural-alunos_modifica.tpl");

exit;

?>
