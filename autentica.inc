<?php

require_once(__DIR__ . "/setup.php"); // Garante a conexao $db, pois alguns arquivos incluem este autentica.inc antes do setup.php

$usuario_nome = isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : '';

$http_host = $_SERVER['HTTP_HOST'];
// echo "Servidor " . $http_host;
$url = $_SERVER['SERVER_NAME'];

// Dados do usuario logado (tabela users)
$usuario_id            = NULL;
$usuario_email         = NULL;
$usuario_nome_completo = NULL;
$usuario_role          = NULL;
$usuario_categoria     = NULL;
$usuario_aluno_id      = NULL;
$usuario_supervisor_id = NULL;
$usuario_professor_id  = NULL;

// Categorias do usuario
$isAluno      = false;
$isProfessor  = false;
$isSupervisor = false;
$isAdmin      = false;

$usuario_ok = false;

if (!empty($usuario_nome)) {
	$sql = "select id, email, nome, role, categoria, ativo, aluno_id, supervisor_id, professor_id " .
			" from users where email = ?";
	$res = $db->Execute($sql, array($usuario_nome));
	if ($res === false) die("Nao foi possivel consultar a tabela users");

	if ($res->RecordCount() == 1 and $res->fields['ativo'] == 1) {
		$usuario_ok = true;

		$usuario_id            = $res->fields['id'];
		$usuario_email         = $res->fields['email'];
		$usuario_nome_completo = $res->fields['nome'];
		$usuario_role          = $res->fields['role'];
		$usuario_categoria     = $res->fields['categoria'];
		$usuario_aluno_id      = $res->fields['aluno_id'];
		$usuario_supervisor_id = $res->fields['supervisor_id'];
		$usuario_professor_id  = $res->fields['professor_id'];

		$isAluno      = ($usuario_role === 'aluno');
		$isProfessor  = ($usuario_role === 'professor');
		$isSupervisor = ($usuario_role === 'supervisor');
		$isAdmin      = ($usuario_role === 'admin');
	}
}

if ($usuario_ok) {
	$mural_autentica = 1;
    $sistema_autentica = 1;
} else {
	$mural_autentica = 0;
    $sistema_autentica = 0;
	header("Location: http://$url/estagio/login.php");
	exit;
}

?>