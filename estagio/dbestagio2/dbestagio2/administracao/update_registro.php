<?php

include_once("../db.inc");
include_once("../setup.php");

/* 
A partir da tabela estagiarios do jEstagioDb
Coloco o número de registro na tabela de estagiarios
utilizando as tabelas alunos e alunosNovos
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
    $sql = "update estagiarios set registro=$registro where estagiarios.id_aluno = $id"; 
    echo $sql . "<br>";
    $resultado = $db->Execute($sql);
    if($resultado == false) die ("Não foi possível atualizar a tabela estagiarios");
    $alunos->MoveNext();
}
*/

// Segundo atualizo os alunosNovos
/*
$sql = "select id, registro from alunosNovos";
$alunos = $db->Execute($sql);
while(!$alunos->EOF)
{
    $registro = $alunos->fields['registro'];
    $id = $alunos->fields['id'];
    $sql = "update estagiarios set registro=$registro where estagiarios.id = $id"; 
    echo $sql . "<br>";
    $resultado = $db->Execute($sql);
    if($resultado == false) die ("Não foi possível atualizar a tabela estagiarios");
    $alunos->MoveNext();
}
*/
?>