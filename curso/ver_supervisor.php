<?php

define("XAJAX", dirname(__DIR__) . "/lib/xajax/");
require_once(XAJAX."xajax.inc.php");
$xajax = new xajax("ver_supervisor.php");
$xajax->registerFunction("ver_supervisor");
$xajax->registerFunction("ver_instituicao");

// Os ids chegam como argumentos xajax nas funcoes abaixo. A leitura direta de
// $_POST que havia aqui era codigo morto (os parametros sombreavam as
// variaveis) e ainda emitia aviso de indice inexistente no PHP 8 a cada
// chamada, o que corromperia a resposta XML do xajax.

function ver_supervisor($id_supervisor) {

    include_once("../db.inc");
    $respostaXajax = new xajaxResponse();

    // Devolvido ao navegador em addAppend("supervisor_id"): int impede injecao
    // no SQL e reflexao de texto arbitrario na resposta.
    $id_supervisor = (int)$id_supervisor;

    $sql  = "select nome, endereco, bairro, municipio, cep, telefone ";
	$sql .= " from curso_inscricao_supervisor ";
    $sql .= " where id=?";
    $resultado = $db->Execute($sql, array($id_supervisor));
    if($resultado === false) die ("Não foi possível consultar a tabela curso_inscricao_supervisor");

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

    // Devolvido ao navegador em addAppend("num_instituicao"): int impede
    // injecao no SQL e reflexao de texto arbitrario na resposta.
    $id_instituicao = (int)$id_instituicao;

    $sql  = "select instituicao, endereco, bairro, municipio, cep, telefone, fax, beneficio, fim_de_semana ";
	$sql .= " from curso_inscricao_instituicao ";
    $sql .= " where id=? order by instituicao";
    $resultado = $db->Execute($sql, array($id_instituicao));
    if($resultado === false) die ("Não foi possível consultar a tabela curso_inscricao_instituicao");

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