<?php

// Script de manutencao: exige sessao autenticada e perfil admin. A permissao e
// conferida aqui, nunca apenas na tela (mesmo criterio de exigirAdmin()).
// autentica.inc ja inclui setup.php, entao $db continua disponivel.
require_once(__DIR__ . "/../autentica.inc");
if (empty($isAdmin)) {
	die("Acesso restrito: apenas administradores podem executar esta manutencao.");
}

/*
A partir da tabela estagiarios
Coloco o numero de registro na tabela de estagiarios
utilizando as tabelas alunos
*/

/*
$sql = "update estagiarios set registro = 0";
$resultado = $db->Execute($sql);
if($resultado == false) die ("Não foi possível zerar a tabela estagiarios");

// Primeiro atualizo os alunos
$sql = "select id, registro from alunos";
$alunos = $db->Execute($sql);
while(!$alunos->EOF)
{
    $registro = $alunos->fields['registro'];
    $id = $alunos->fields['id'];
    $sql = "update estagiarios set registro=$registro where estagiarios.aluno_id = $id"; 
    $resultado = $db->Execute($sql);
    if($resultado == false) die ("Não foi possível atualizar a tabela estagiarios");
    $alunos->MoveNext();
}
*/

?>