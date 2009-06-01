<?php

define("XAJAX","/usr/local/htdocs/html/xajax/");
require_once(XAJAX."xajax.inc.php");
$xajax = new xajax("ver_supervisor.php");
$xajax->registerFunction("ver_supervisor");
$xajax->registerFunction("ver_instituicao");

$id_supervisor = $_POST['id_supervisor'];
$id_instituicao = $_POST['id_instituicao'];

function ver_supervisor($id_supervisor) {

    include_once("../db.inc");
    $respostaXajax = new xajaxResponse();

    $sql  = "select nome, endereco, bairro, municipio, cep, telefone ";
	$sql .= " from curso_inscricao_supervisor "; 
    $sql .= " where id=$id_supervisor";
    $resultado = $db->Execute($sql);
    if($resultado_=== false) die ("Não foi possível consultar a tabela curso_inscricao_supervisor");

    while (!$resultado->EOF) {
			$nome = $resultado->fields['nome'];
			$endereco = $resultado->fields['endereco'];
			$bairro = $resultado->fields['bairro'];
			$municipio = $resultado->fields['municipio'];
			$cep = $resultado->fields['cep'];
			$telefone = $resultado->fields['telefone'];
			
			$respostaXajax->addAppend("supervisor_id","value",$id_supervisor);
			$respostaXajax->addAppend("supervisor_nome","value",$nome);
			$respostaXajax->addAppend("supervisor_endereco","value",$endereco);
			$respostaXajax->addAppend("supervisor_bairro","value",$bairro);
			$respostaXajax->addAppend("supervisor_municipio","value",$municipio);
			$respostaXajax->addAppend("supervisor_cep","value",$cep);
			$respostaXajax->addAppend("supervisor_telefone","value",$telefone);
			
			$resultado->MoveNext();
    }
    
    return $respostaXajax;

}

function ver_instituicao($id_instituicao) {

    include_once("../db.inc");
    $respostaXajax = new xajaxResponse();

    $sql  = "select instituicao, endereco, bairro, municipio, cep, telefone, fax, beneficio, fim_de_semana ";
	$sql .= " from curso_inscricao_instituicao "; 
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

$xajax->processRequests();

?>