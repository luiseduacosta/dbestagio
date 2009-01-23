<?php

/*
 * Created on 13/06/2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../../autoriza.inc");

// include_once("mural-autentica.inc");
$ordem = $_GET['ordem'];

include_once("../../db.inc");
include_once("../../setup.php");

// Alunos conhecidos => flag = 1
$sql = 'select mural_inscricao.id, mural_inscricao.data, '
        . ' alunos.id, alunos.registro, alunos.nome, alunos.telefone, alunos.celular, alunos.email, '
        . ' max(estagiarios.nivel) as nivel '
        . ' from mural_inscricao '
        . ' inner join alunos on mural_inscricao.id_aluno = alunos.registro '
        . ' inner join mural_estagio on mural_estagio.id = mural_inscricao.id_instituicao '
        . ' inner join estagiarios on estagiarios.id_aluno = alunos.id '
	. ' where estagiarios.periodo != \'' . PERIODO_ATUAL . '\' ' 
	. ' and mural_inscricao.periodo = \'' . PERIODO_ATUAL . '\' '
        . ' group by alunos.registro '
        . ' order by alunos.nome';

// echo $sql . "<br";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela mural_inscricao");
$i = 0;
while(!$resultado->EOF) {

	if (empty($ordem)) {
		$ordem = "nome";
	} else {
		$indice = $ordem;
	}
	$inscritos[$i][$ordem] = $$indice;

	$inscritos[$i]['registro'] = $resultado->fields['registro'];
	$inscritos[$i]['nome'] = $resultado->fields['nome'];
	$inscritos[$i]['telefone'] = $resultado->fields['telefone'];
	$inscritos[$i]['celular'] = $resultado->fields['celular'];
	$inscritos[$i]['email'] = $resultado->fields['email'];
	$inscritos[$i]['nivel'] = $resultado->fields['nivel'];
	$inscritos[$i]['flag'] = 1; // Aluno conhecido

	$data = $resultado->fields['data'];

	$dataCorrigida = split("-",$data);
	$dataSQL = $dataCorrigida[2] . "-" . $dataCorrigida[1] . "-" . $dataCorrigida[0];
	
	$inscritos[$i]['data'] = $dataSQL;

	$id_aluno = $resultado->fields['id'];
	// Capturo a última instituicao na qual o aluno fez estagio
	$sqlInstituicao = "select instituicao, periodo from estagio "
	. " inner join estagiarios on estagiarios.id_instituicao = estagio.id "
	. " where estagiarios.id_aluno = $id_aluno " 
	. " order by estagiarios.nivel";
	$resultadoInstituicao = $db->Execute($sqlInstituicao);
	if($resultadoInstituicao === false) die ("Não foi possível consultar a tabelas estagio e estagiarios");	
	while (!$resultadoInstituicao->EOF) {
		$periodo = $resultadoInstituicao->fields['periodo'];
		if ($periodo != PERIODO_ATUAL) {
			$instituicaoEstagio = $resultadoInstituicao->fields['instituicao'];
		}
		$resultadoInstituicao->MoveNext();
	}

	$inscritos[$i]['instituicao'] = $instituicaoEstagio;

	$i++;
	$resultado->MoveNext();
}

if (sizeof($inscritos) != 0) {
	sort($inscritos);
}

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("mural_autentica",$mural_autentica);
$smarty->assign("totalAlunos",$i);
$smarty->assign("inscritos",$inscritos);
$smarty->display("alunosConhecidos.tpl");

?>
