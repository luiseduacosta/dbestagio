<?php

require_once(__DIR__ . "/setup.php"); // Garante a conexao $db, pois alguns arquivos incluem este autentica.inc antes do setup.php
require_once(__DIR__ . "/libphp/sessao.php");

// Servidor onde esta sendo executado o programa (usado nos redirecionamentos)
$url = $_SERVER['SERVER_NAME'];

// Dados do usuario logado (tabela users)
$usuario_id            = NULL;
$usuario_email         = NULL;
$usuario_nome          = NULL;
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

// Usuario autenticado = sessao criada no login (verifica_login.php) e relida da
// tabela users a cada requisicao: usuario desativado ou removido perde o acesso
// na hora. Nao se usa mais nenhum cookie de identificacao.
$usuario = sessao_usuario_atual();

if ($usuario !== null) {
	$usuario_ok = true;

	$usuario_id            = (int)$usuario['id'];
	$usuario_email         = $usuario['email'];
	$usuario_nome          = $usuario['nome'];
	$usuario_nome_completo = $usuario['nome'];
	$usuario_role          = $usuario['role'];
	$usuario_categoria     = $usuario['categoria'];
	$usuario_aluno_id      = $usuario['aluno_id'];
	$usuario_supervisor_id = $usuario['supervisor_id'];
	$usuario_professor_id  = $usuario['professor_id'];

	$isAluno      = ($usuario_role === 'aluno');
	$isProfessor  = ($usuario_role === 'professor');
	$isSupervisor = ($usuario_role === 'supervisor');
	$isAdmin      = ($usuario_role === 'admin');
}

if ($usuario_ok) {
	$mural_autentica = 1;
    $sistema_autentica = 1;
} else {
	$mural_autentica = 0;
    $sistema_autentica = 0;
	$url = $_SERVER['SERVER_NAME'];
	header("Location: http://$url/estagio/login.php");
	exit;
}

?>
