<?php

$id_instituicao = $_POST['id_instituicao'];
require_once("ajaxInstituicao.php");

function verInstituicao($id_instituicao) {

    include_once("../db.inc");
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

$xajax->processRequests();

?>