<?php

include_once("../setup.php");

$sql = "select * from curso_inscricao_supervisor";
$resultado = $db->Execute($sql);
while(!$resultado->EOF) {
	$id = $resultado->fields['id'];
	$nome = $resultado->fields['nome'];
	$endereco = $resultado->fields['endereco'];
	$bairro = $resultado->fields['bairro'];
	$municipio = $resultado->fields['municipio'];
	$cep = $resultado->fields['cep'];
	$codigo_tel = $resultado->fields['codigo_tel'];
	$telefone = $resultado->fields['telefone'];
	$codigo_cel = $resultado->fields['codigo_cel'];
	$celular = $resultado->fields['celular'];
	$email = $resultado->fields['email'];
	$escola = $resultado->fields['escola'];
	$ano_formatura = $resultado->fields['ano_formatura'];
	$cress = $resultado->fields['cress'];
	$region = $resultado->fields['region'];
	$outros_estudos = $resultado->fields['outros_estudos'];
	$area_curso = $resultado->fields['area_curso'];
	$ano_curso = $resultado->fields['ano_curso'];
	$cargo = $resultado->fields['cargo'];
	$num_inscricao = $resultado->fields['num_inscricao'];
	$curso_turma = $resultado->fields['curso_turma'];
	$observacoes = $resultado->fields['observacoes'];

	// Insero o supervisor
	$sql_supervisor  =  "insert into supervisores ";
	$sql_supervisor .= "(nome, endereco, bairro, municipio, cep, codigo_tel, telefone, codigo_cel, celular, email, escola, ano_formatura, cress, regiao, outros_estudos, area_curso, ano_curso, cargo, num_inscricao, curso_turma) ";
	$sql_supervisor .= "values('$nome','$endereco','$bairro','$municipio','$cep','$codigo_tel','$telefone','$codigo_cel','$celular','$email','$escola','$ano_formatura','$cress','$regiao','$outros_estudos','$area_curso','$ano_curso','$cargo','$quantidade','$turma')";
	echo $sql_supervisor . "<br>";
	$res_supervisor = $db->Execute($sql_supervisor);
	if($res_supervisor === false) die ("N�o foi poss�vel inserir o registro na tabela supervisores");
	
	$res_ultimo_supervisor = $db->Execute("select max(id) as ultimo_registro from supervisores");
	if($res_ultima_supervisor === false) die ("N�o foi poss�vel consultar a tabela supervisores");
	$id_supervisor = $res_ultimo_supervisor->fields['ultimo_registro'];
	
	$sql_inst_super = "select * from curso_inst_super where id_supervisor=$id_supervisor";
	$resultado_inst_super = $db->Execute($sql_inst_super);
	while(!$resultado_inst_super->EOF) {	

		$id_instituicao = $resultado_inst_super->fields['id_instituicao'];
		
		/*
		 * Tenho que pensar mais o que fazer aqui
		 */
		
		$sql_instituicao = "select * from curso_inscricao_instituicao where id=$id_instituicao";
		$res_instituicao = $db->Execute($sql_instituicao);
		while(!$res_instituicao->EOF) {
			$id = $res_instituicao->fields['id'];
			$instituicao = $res_instituicao->fields['instituicao'];	
			$inst_endereco = $res_instituicao->fields['endereco'];
			$inst_bairro = $res_instituicao->fields['bairro'];
			$inst_municipio = $res_instituicao->fields['municipio'];
			$inst_cep = $res_instituicao->fields['cep'];
			$inst_telefone = $res_instituicao->fields['telefone'];
			$inst_fax = $res_instituicao->fields['fax'];
			$beneficio = $res_instituicao->fields['beneficio'];
			$fin_de_semana = $res_instituicao->fields['fin_de_semana'];
			
			$sql_instituicao  = "insert estagio ";
			$sql_instituicao .= "(instituicao, endereco, bairro, municipio, cep, telefone, fax, beneficio, fim_de_semana) ";
			$sql_instituicao .= "values('$instituicao','$inst_endereco','$inst_bairro','$inst_municipio','$inst_cep','$inst_telefone','$inst_fax','$beneficio','$fim_de_semana')";
			echo $sql_instituicao . "<br>";
			$res_institucao = $db->Execute($sql_instituicao);
			if($res_instituicao === false) die ("N�o foi poss�vel inserir o registro na tabela curso_incricao_instituicao");

			$res_ultima_instituicao = $db->Execute("select max(id) as ultimo_registro from estagio");
			if($res_ultima_instituicao === false) die ("N�o foi poss�vel consultar a tabela estagio");
			$id_instituicao = $res_ultima_instituicao->fields['ultimo_registro'];
		
			$sql_inst_sup = "insert into curso_inst_super (id_supervisor, id_instituicao) values('$id_supervisor','$id_instituicao')";
			$res_inst_sup = $db->Execute($sql_inst_sup);
			if($res_inst_sup === false) die ("N�o foi poss�vel inserir o registro na tabela curso_inst_sup");
			// echo $sql_inst_sup . "<br>";
		
			$res_instituicao->MoveNext();
		}
		
		$resultado_inst_super->MoveNext();
	}

	$resultado->MoveNext();
}

?>