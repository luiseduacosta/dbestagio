<?php require_once("ajaxInstituicao.php"); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
    <title>Inscri&ccedil;&atilde;o para curso de supervisores</title>
	<!-- Autocompletar //-->
	<script type="text/javascript" src="../lib/jquery.js"></script>
	<script type='text/javascript' src='../lib/jquery.autocomplete.js'></script>
	<link rel="stylesheet" type="text/css" href="../lib/jquery.autocomplete.css" />
	<script type="text/javascript">
		
	function selectItem(li) {
		if (li.extra) {
			// alert(" " + li.extra[0] + " selecionado.");
		}
	}
	
	function formatItem(row) {
		return row[0] + "<br><i>" + row[1] + "</i>";
	}
	
	$(document).ready(function() {
		$("#nome").autocomplete("supervisores.php", { minChars:3, matchSubset:1, matchContains:1, cacheLength:10, onItemSelect:selectItem, formatItem:formatItem, selectOnly:1 });
	});
	
	</script>

	<script src="../lib/jquery.maskedinput-1.2.1.pack.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(function() {
		 $("#cpf").mask("999999999-99");
		 $("#cep").mask("99999-999");
		 $("#telefone").mask("9999.9999");
 		 $("#celular").mask("9999.9999");
		 $("#ano_formatura").mask("9999");
 		 $("#ano_curso").mask("9999");
		 $("#instituicao_cep").mask("99999-999");
	});
	</script>

<?php $xajax->printJavascript(); ?>
<script language="JavaScript" type="text/javascript">
<!--
function confirma() {
    var nome, cpf, endereco, bairro, municipio, cep, escola, ano, email, cress, instituicao;

    nome=document.inscricao.nome.value;
    cpf=document.inscricao.cpf.value;
    email=document.inscricao.email.value;
    cress=document.inscricao.cress.value;
    instituicao=document.inscricao.instituicaoNova.value;

    if(nome=="") {
    	alert("Você precisa informar o seu nome");
		document.inscricao.nome.focus();
		return false;
	}

    if(cpf=="") {
    	alert("Você precisa informar o seu CPF");
		document.inscricao.cpf.focus();
		return false;
	}

    if(email=="") {
    	alert("É importante o seu e-mail para nossa comunicação");
		document.inscricao.email.focus();
		return false;
	}

    if(cress=="") {
    	alert("Favor informar o seu número de registro no CRESS 7a. região");
		document.inscricao.cress.focus();
		return false;
	}

    if(instituicao=="") {
    	alert("Informe a instituição em que trabalha");
		document.inscricao.instituicao.focus();
		return false;
	}

    return true;
}

function verifica_nome() {
    var nome;
    nome=document.inscricao.nome.value;
    if(nome=="") {
	alert("Você precisa informar o seu nome");
	document.inscricao.nome.focus();
	return false;
} else {
	return true;
    }
}

function verifica_cpf() {
    var cpf;
    cpf=document.inscricao.cpf.value;
    if(cpf=="") {
	alert("Você precisa informar o seu CPF");
	document.inscricao.cpf.focus();
	return false;
} else {
	return true;
    }
}

function verifica_email() {
    var email;
    email=document.inscricao.email.value;
    if(email=="") {
	alert("É importante o e-mail para nossa comunicação");
	document.inscricao.email.focus();
	return false;
} else {
	return true;
    }
}

function verifica_cress() {
    var cress;
    cress=document.inscricao.cress.value;
    if(cress=="") {
	alert("Você precisa informar o número de registro no CRESS 7a. região");
	document.inscricao.cress.focus();
	return false;
} else {
	return true;
    }
}

function verifica_instituicao() {
    var instituicao;
    instituicao=document.inscricao.instituicaoNova.value;
    if(instituicao=="") {
		alert("Você precisa informar a instituição na que trabalha");
		document.inscricao.instituicaoNova.focus();
		return false;
    } else {
		return true;
    }
}

function contacarateres() {
    var quantidade = document.getElementById("cep").value.length;
	var caraterDigitado = document.getElementById("cep").value;
	if (quantidade == 5) {
		caraterDigitado = document.getElementById("cep").value;
		caraterDigitado = caraterDigitado + "-";
	}
	var carateres = document.getElementById("quantidade");
	carateres.innerHTML = "Formato: 99999-999. Quantidade de carateres digitados: " +quantidade;
	var cep = document.getElementById("cep");
	cep.value = caraterDigitado;
    if(quantidade > 9) {
		alert("O limite do campo é de 9 carateres");
	}
}

function verInstituicao() {
    var id_instituicao = document.getElementById("id_instituicao").value;

    var num_instituicao = document.getElementById("num_instituicao");
    num_instituicao.value = "";
	
    var instituicao = document.getElementById("instituicaoNova");
    instituicao.value = "";

    var endereco = document.getElementById("instituicao_endereco");
    endereco.value = "";
    
    var bairro = document.getElementById("instituicao_bairro");
    bairro.value = "";
    
    var municipio = document.getElementById("instituicao_municipio");
    municipio.value = "";

    var cep = document.getElementById("instituicao_cep");
    cep.value = "";

    var telefone = document.getElementById("instituicao_telefone");
    telefone.value = "";

    var fax = document.getElementById("instituicao_fax");
    fax.value = "";

    var beneficio = document.getElementById("instituicao_beneficio");
    beneficio.value = "";

    var fim_de_semana = document.getElementById("fim_de_semana");
    fax.value = "";
    
    xajax_verInstituicao(id_instituicao);
    // alert(id_instituicao);
    return true;
}
//-->
</script>
<style type="text/css">
@import url(formulario_curso.css);
</style>
</head>
<body>

<?php

include_once("../db.inc");
include_once("../setup.php");

$turma = TURMA;

$data_encerramento = strtotime(ENCERRAMENTO);
$hoje = strtotime("now");

$sql  = "select count(*) as quantidade ";
$sql .= " from curso_inscricao_supervisor ";
$sql .= " where curso_turma=$turma";
// echo $sql . "<br>";
$res_sql = $db->Execute($sql);
if($res_sql === false) die ("Nao foi possivel consultar a tabela curso_inscricao_supervisor");
$quantidade = $res_sql->fields['quantidade'];

// var_dump($data_encerramento);
// var_dump($hoje);
if($hoje > $data_encerramento) {
    // echo "<h2>Inscri&ccedil;&otilde;es encerradas</h2>";
    echo "<h2>Inscrições para 2009-1 a partir do 02/03/2009</h2>";
    // include("doc/selecionados2008.html");
    exit;
}

?>

<form action="inscricao_curso.php" name="inscricao" method="post">
<h1>Formulário de inscrição para o <?php echo $turma; ?>o. curso de
capacitação profissional para supervisores:
</h1>

<h2>"Desafios para intervenção profissional do assistente social"</h2>

<div align="center">
<table border="1">
<tbody>

<tr>
<td colspan="2" class="coluna_centralizada">
Quantidade de inscrições realizadas: <?php echo $quantidade; ?>
</td>
</tr>

<tr>
<td colspan="2" class="rodape">
<b>Dados do supervisor</b>
</td>
</tr>

<tr>
<td width="30%">Nome*: </td>
<td width="70%">
<input type="text" name="nome" id="nome" value="<?php echo $nome; ?>" maxlength="70" size="45" onBlur="return verifica_nome();">
CPF*: <input type="text" name="cpf" id="cpf" value="<?php echo $cpf; ?>" maxlength="12" size="12" onBlur="return verifica_cpf();"> 
</td>
</tr>

<tr>
<td>Endereço: </td>
<td><input type="text" name="endereco" id="endereco" value="<?php echo $endereco; ?>" maxlength="100" size="50"></td>
</tr>

<tr>
<td>Bairro: </td>
<td>
<input type="text" name="bairro" id="bairro" value="<?php echo $bairro; ?>" maxlength="30" size="20">
Município: 
<input type="text" name="municipio" id="municipio"  value="<?php echo $municipio; ?>" maxlength="30" size="20">
CEP:
<input type="text" name="cep" id="cep" value="<?php echo $cep; ?>" maxlength="9" size="9">
</td>
</tr>

<tr>
<td>Telefone: </td>
<td>
Código: <input type="text" name="codigo_tel" id="codigo_tel" maxlength="2" size="2" value="21">
<input type="text" name="telefone" id="telefone" value="<?php echo $telefone; ?>" maxlength="9" size="9">
Celular: 
<input type="text" name="codigo_cel" id="codigo_cel" maxlength="2" size="2" value="21">
<input type="text" name="celular" id="celular" value="<?php echo $celular; ?>" maxlength="9" size="9">
</td>
</tr>

<tr>
<td>E-mail*: </td>
<td><input type="text" name="email" id="email" value="<?php echo $email; ?>" maxlength="50" size="50" onBlur="return verifica_email();"></td>
</tr>

<tr>
<td>Escola em que se formou: </td>
<td><input type="text" name="escola" id="escola" value="<?php echo $escola; ?>" maxlength="70" size="30"></td>
</tr>

<tr>
<td>Ano em que se formou: </td>
<td><input type="text" name="ano_formatura" id="ano_formatura" value="<?php echo $ano_formatura; ?>" maxlength="4" size="4"></td>
</tr>

<tr>
<td>No. de registro no CRESS*</td>
<td>
<input type="text" name="cress" id="cress" value="<?php echo $cress; ?>" maxlength="15" size="10" onBlur="return verifica_cress();">
<span style="text-align:right">Região: </span><input type="text" name="regiao" id="regiao" maxlength="2" size="1" value="7">
Digite o número 0 se ainda não tem número de CRESS
</td>
</tr>

<tr>
<td>Outros estudos realizados:</td>
<td>Especialização:
<input type="radio" name="outros_estudos" id="outros_estudos" value="especializacão">
Mestrado:
<input type="radio" name="outros_estudos" id="outros_estudos" value="mestrado">
Doutorado
<input type="radio" name="outros_estudos" id="outros_estudos" value="doutorado">
</td>
</tr>

<tr>
<td>Área em que foi realizado o curso anteriormente mencionado</td>
<td><input type="text" name="area_curso" id="area_curso" value="<?php echo $area_curso; ?>" maxlength="40" size="30"></td>
</tr>

<tr>
<td>Ano em que foi concluí­do o curso</td>
<td><input type="text" name="ano_curso" id="ano_curso" value="<?php echo $ano_curso; ?>" maxlength="4" size="4"></td>
</tr>

<tr>
<td colspan="2" class="rodape">
<b>Dados da instituição</b></td>
</tr>

<?php
$sql  = "select id, instituicao ";
$sql .= " from estagio ";
// $sql .= " group by instituicao ";
$sql .= " order by instituicao";
$resultado = $db->Execute($sql);
if($resultado_=== false) die ("Não foi possivel consultar a tabela estagio");
?>
<tr>
<td>Selecione a instituição ou programa*: </td>
<td>
<select name="id_instituicao" id="id_instituicao" size="1" onChange="return verInstituicao();">
<option value=0>Selecione a instituição aqui, caso contrário, cadastre uma nova instituição</option>
<?php
while (!$resultado->EOF) {
    $id_instituicao = $resultado->fields['id'];
    $instituicao = $resultado->fields['instituicao'];
    echo "
    <option value=$id_instituicao>$instituicao</option>
    ";
    $resultado->MoveNext();
}
?>
</select>
</td>
</tr>

<input type="hidden" name="num_instituicao" id="num_instituicao">

<tr>
<td>Nome da instituição ou programa*: </td>
<td><input type="text" maxlength="75" size="50" name="instituicaoNova" id="instituicaoNova" onBlur="return verifica_instituicao();"></td>
</tr>

<tr>
<td>Endereço: </td>
<td><input type="text" maxlength="105" size="50" name="instituicao_endereco" id="instituicao_endereco"></td>
</tr>

<tr>
<td>Bairro: </td>
<td><input type="text" maxlength="30" size="30" name="instituicao_bairro" id="instituicao_bairro"></td>
</tr>

<tr>
<td>Município: </td>
<td><input type="text" maxlength="30" size="30" name="instituicao_municipio" id="instituicao_municipio"></td>
</tr>

<tr>
<td>CEP: </td>
<td><input type="text" maxlength="9" size="10" name="instituicao_cep" id="instituicao_cep"></td>
</tr>

<tr>
<td>Telefone: </td>
<td><input type="text" maxlength="50" size="50" name="instituicao_telefone" id="instituicao_telefone"></td>
</tr>

<tr>
<td>Fax: </td>
<td><input type="text" maxlength="15" size="15" name="instituicao_fax" id="instituicao_fax"></td>
</tr>

<tr>
<td>O estagiário recebe algum benefí­cio económico (ex. bolsa, vale transporte, etc.): </td>
<td><input type="text" maxlength="50" size="50" name="instituicao_beneficio" id="instituicao_beneficio"></td>
</tr>

<tr>
<td>Tem estágio no final de semana: </td>
<td>
Não:<input type="radio" name="fim_de_semana" id="fim_de_semana" value="0" checked>
Sim:<input type="radio" name="fim_de_semana" id="fim_de_semana" value="1">
Parcialmente:<input type="radio" name="fim_de_semana" id="fim_de_semana" value="2">
</td>
</tr>

<tr>
<td colspan="2" class="coluna_centralizada">
<input type="submit" name="enviar" value="Enviar" onClick="return confirma();">
<input type="reset" name="limpar" value="Limpar">
</td>
</tr>

</tbody>
</table>
</div>

</form>
	
</body>
</html>