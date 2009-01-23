<?php

$id_instituicao = $_POST['id_instituicao'];
$id_supervisor  = $_POST['id_supervisor'];
require_once("ajaxInstituicao.php");

function ver_Instituicao($id_instituicao) {

    include_once("../../db.inc");
    $respostaXajax = new xajaxResponse();

    $sql  = "select instituicao, endereco, bairro, municipio, cep, telefone, fax, beneficio, fim_de_semana ";
    $sql .= " from estagio "; 
    $sql .= " where id=$id_instituicao order by instituicao";
    $resultado = $db->Execute($sql);
    if($resultado_=== false) die ("Não foi possível consultar a tabela curso_inscricao_instituicao");

    while (!$resultado->EOF) {
			$instituicao = $resultado->fields['instituicao'];
			$endereco = $resultado->fields['endereco'];
			$bairro = $resultado->fields['bairro'];
			$municipio = $resultado->fields['municipio'];
			$cep = $resultado->fields['cep'];
			$telefone = $resultado->fields['telefone'];
			$fax = $resultado->fields['fax'];
			$beneficio = $resultado->fields['beneficio'];
			$fim_de_semana = $resultado->fields['fim_de_semana'];
			
			$respostaXajax->addAppend("num_instituicao","value",$id_instituicao);
			$respostaXajax->addAppend("instituicaoNova","value",$instituicao);
			$respostaXajax->addAppend("instituicao_endereco","value",$endereco);
			$respostaXajax->addAppend("instituicao_bairro","value",$bairro);
			$respostaXajax->addAppend("instituicao_municipio","value",$municipio);
			$respostaXajax->addAppend("instituicao_cep","value",$cep);
			$respostaXajax->addAppend("instituicao_telefone","value",$telefone);
			$respostaXajax->addAppend("instituicao_fax","value",$fax);
			$respostaXajax->addAppend("instituicao_beneficio","value",$beneficio);
			$respostaXajax->addAppend("fim_de_semana","value",$fim_de_semana);
			
			$resultado->MoveNext();
    }
    
    return $respostaXajax;

}

function ver_Supervisor($id_supervisor) {

    include_once("../../db.inc");
    $respostaXajax = new xajaxResponse();

    $sql  = "select supervisores.nome, supervisores.endereco, supervisores.bairro, supervisores.municipio, supervisores.cep, supervisores.codigo_tel, supervisores.telefone, supervisores.codigo_cel, supervisores.celular, supervisores.email";
	$sql .= ", supervisores.cress, supervisores.regiao, supervisores.escola, supervisores.ano_formatura, supervisores.outros_estudos, supervisores.area_curso, supervisores.ano_curso ";
    $sql .= " from supervisores ";
    $sql .= " where supervisores.id=$id_supervisor order by supervisores.nome";
    // echo $sql . "<br>";
    
    $resultado = $db->Execute($sql);
    if($resultado_=== false) die ("Não foi possível consultar a tabela curso_inscricao_instituicao");

    while (!$resultado->EOF) {
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
			$cress = $resultado->fields['cress'];
			$regiao = $resultado->fields['regiao'];
			$escola = $resultado->fields['escola'];
			$ano_formatura = $resultado->fields['ano_formatura'];
			$outros_estudos = $resultado->fields['outros_estudos'];
			$area_curso = $resultado->fields['area_curso'];
			$ano_curso = $resultado->fields['ano_curso'];
			
			$respostaXajax->addAppend("num_supervisor","value",$id_supervisor);
			$respostaXajax->addAppend("supervisor_novo","value",$nome);
			$respostaXajax->addAppend("endereco","value",$endereco);
			$respostaXajax->addAppend("bairro","value",$bairro);
			$respostaXajax->addAppend("municipio","value",$municipio);
			$respostaXajax->addAppend("cep","value",$cep);
			$respostaXajax->addAppend("codigo_tel","value",$codigo_tel);
			$respostaXajax->addAppend("telefone","value",$telefone);
			$respostaXajax->addAppend("codigo_cel","value",$codigo_cel);
			$respostaXajax->addAppend("celular","value",$celular);
			$respostaXajax->addAppend("email","value",$email);
			$respostaXajax->addAppend("escola","value",$escola);
			$respostaXajax->addAppend("ano_formatura","value",$ano_formatura);
			$respostaXajax->addAppend("cress","value",$cress);
			$respostaXajax->addAppend("regiao","value",$regiao);			
			$respostaXajax->addAppend("outros_estudos","value",$outros_estudos);
			$respostaXajax->addAppend("area_curso","value",$area_curso);
			$respostaXajax->addAppend("ano_curso","value",$ano_curso);
			
			$resultado->MoveNext();
    }
    
    return $respostaXajax;

}

$xajax->processRequests();

?>