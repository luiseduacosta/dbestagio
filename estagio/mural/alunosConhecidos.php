<?php

/*
 * Created on 13/06/2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once("../autoriza.inc");

// include_once("mural-autentica.inc");
$ordem = $_GET['ordem'];

include_once("../db.inc");
include_once("../setup.php");

$sql_inscritos  = "select mural_inscricao.id_aluno "; 
$sql_inscritos .= " , mural_inscricao.periodo as mural_periodo ";
$sql_inscritos .= " , max(estagiarios.periodo) as estagio_periodo, max(estagiarios.nivel) as nivel ";
$sql_inscritos .= " , alunos.nome, alunos.telefone, alunos.celular, alunos.email ";
$sql_inscritos .= " from mural_inscricao ";
$sql_inscritos .= " inner join estagiarios on mural_inscricao.id_aluno = estagiarios.registro "; 
$sql_inscritos .= " inner join alunos on mural_inscricao.id_aluno = alunos.registro "; 
$sql_inscritos .= " where mural_inscricao.periodo = '" . PERIODO_ATUAL  . "'";
$sql_inscritos .= " group by mural_inscricao.id_aluno ";

// echo $sql_inscritos . "<br>";

$resultado_inscritos = $db->Execute($sql_inscritos);
if($resultado_inscritos === false) die ("Não foi possível consultar as tabelas");	
$i = 1;
while (!$resultado_inscritos->EOF) {

	if (empty($ordem)) {
		$ordem = "nome";
	} else {
		$indice = $ordem;
	}
	
	$inscritos[$i][$ordem] = $$indice;
	
	$id_aluno = $resultado_inscritos->fields['id_aluno'];
	$nivel = $resultado_inscritos->fields['nivel'];
	$id_instituicao = $resultado_inscritos->fields['id_instituicao'];
	
	$nome = $resultado_inscritos->fields['nome'];
	$telefone = $resultado_inscritos->fields['telefone'];
	$celular = $resultado_inscritos->fields['celular'];
	$email = $resultado_inscritos->fields['email'];	

	$mural_periodo = $resultado_inscritos->fields['mural_periodo'];
	$estagio_periodo = $resultado_inscritos->fields['estagio_periodo'];
	if ($mural_periodo == $estagio_periodo) {
		$inscritos[$i]['inscrito'] = 1; // Inscrito
	} else {
		$inscritos[$i]['inscrito'] = 0; // Nao inscrito
	}

	// echo $i++ . " " . $id_aluno . " " . $nome .  " " . $telefone .  " " . $celular .  " " . $email . "<br>";

	$inscritos[$i]['registro'] = $id_aluno;
	$inscritos[$i]['nivel'] = $nivel;

	$inscritos[$i]['nome'] = $nome;
	$inscritos[$i]['telefone'] = $telefone;
	$inscritos[$i]['celular'] = $celular;
	$inscritos[$i]['email'] = $email;

	$inscritos[$i]['estagio_periodo'] = $estagio_periodo;

	$inscritos[$i]['data'] = date("d-m-Y",strtotime($resultado->fields['data']));
	
	// Capturo o nome da instituicao na qual o aluno fez estagio
	$sql_instituicao = "select instituicao from estagio 
	inner join estagiarios on estagio.id = estagiarios.id_instituicao 
	where estagiarios.registro = '$id_aluno' and estagiarios.periodo = (select max(periodo) from estagiarios where registro = $id_aluno)";
	// echo $sql_instituicao . "<br>";
	$resultado_instituicao = $db->Execute($sql_instituicao);
	if($resultado_instituicao === false) die ("Não foi possível consultar a tabela estagio");	
	$instituicao = $resultado_instituicao->fields['instituicao'];

	$inscritos[$i]['instituicao'] = $instituicao;
	
	$i++;
	
	$resultado_inscritos->MoveNext();
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
