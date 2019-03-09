<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <title>Cadastro de supervisores</title>
<script language="JavaScript" type="text/javascript">
<!--
function confirma() {
    var nome, email, cress, instituicao;

    nome=document.inscricao.nome.value;
    email=document.inscricao.email.value;
    cress=document.inscricao.cress.value;
    instituicao=document.inscricao.instituicaoNova.value;

    if (nome=="") {
    	alert("Você precisa informar o seu nome");
	document.inscricao.nome.focus();
	return false;
	}

    if (email=="") {
    	alert("É importante o seu e-mail para nossa comunicação");
	document.inscricao.email.focus();
	return false;
	}

    if (cress=="") {
    	alert("Favor informar o seu número de registro no CRESS");
	document.inscricao.cress.focus();
	return false;
	}

    if (instituicao=="") {
    	alert("Informe a instituição em que trabalha");
	document.inscricao.instituicao.focus();
	return false;
	}

    return true;
}

function verifica_nome() {
    var nome;
    nome=document.inscricao.nome.value;
    if (nome=="")
    {
	alert("Você precisa informar o seu nome");
	document.inscricao.nome.focus();
	return false;
    }
    else
    {
	return true;
    }
}

function verifica_email() {
    var email;
    email=document.inscricao.email.value;
    if (email=="")
    {
	alert("É importante o e-mail para nossa comunicação");
	document.inscricao.email.focus();
	return false;
    }
    else
    {
	return true;
    }
}

function verifica_cress() {
    var cress;
    cress=document.inscricao.cress.value;
    if (cress=="")
    {
	alert("Você precisa informar o número de registro no CRESS 7a. região");
	document.inscricao.cress.focus();
	return false;
    }
    else
    {
	return true;
    }
}

function verifica_instituicao() {
    var instituicao;
    instituicao=document.inscricao.instituicaoNova.value;
    if (instituicao=="") {
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
//-->
</script>
<style type="text/css">
@import url(../../estagio.css);
</style>

</head>

<body>

<?php

include_once("../../setup.php");
include_once("../../autentica.inc");

if ($sistema_autentica == 0) {
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=http://$url/estagio/login.php'>";
	exit;
}

?>

<form action="inserir.php" name="cadastro" method="post">

<div align="center">
<table border="1">
<tbody>

<tr>
<td colspan="2" class="rodape">
<b>Dados do supervisor</b>
</td>
</tr>

<tr>
<td width="30%">Nome: </td>
<td width="70%">
<input type="text" name="nome" id="supervisor_novo" maxlength="70" size="50" onBlur="return verifica_nome()">
CPF: 
<input type="text" name="cpf" id="cpf" maxlength="12" size="12">
</td>
</tr>

<tr>
<td>Endereço: </td>
<td><input type="text" name="endereco" id="endereco" maxlength="100" size="50"></td>
</tr>

<tr>
<td>Bairro: </td>
<td><input type="text" name="bairro" id="bairro" maxlength="30" size="30"></td>
</tr>

<tr>
<td>Município: </td>
<td><input type="text" name="municipio" id="municipio" maxlength="30" size="30"></td>
</tr>

<tr>
<td>CEP:</td>
<td><input type="text" name="cep" id="cep" maxlength="9" size="9" onkeyup="contacarateres();">
<span id="quantidade"></span></td>
</tr>

<tr>
<td>Telefone: </td>
<td>
Código: <input type="text" name="codigo_tel" id="codigo_tel" maxlength="2" size="2" value="21">
<input type="text" name="telefone" id="telefone" maxlength="9" size="9"></td>
</tr>

<tr>
<td>Celular: </td>
<td>
Código: <input type="text" name="codigo_cel" id="codigo_cel" maxlength="2" size="2" value="21">
<input type="text" name="celular" id="celular" maxlength="9" size="9"></td>
</tr>

<tr>
<td>E-mail: </td>
<td><input type="text" name="email" id="email" maxlength="50" size="50" onBlur="return verifica_email();"></td>
</tr>

<tr>
<td>Escola em que se formou: </td>
<td><input type="text" name="escola" id="escola" maxlength="70" size="30"></td>
</tr>

<tr>
<td>Ano em que se formou: </td>
<td><input type="text" name="ano_formatura" id="ano_formatura" maxlength="4" size="4"></td>
</tr>

<tr>
<td>No. de registro no CRESS</td>
<td>
<input type="text" name="cress" id="cress" maxlength="15" size="10" onBlur="return verifica_cress()">
<span style="text-align:right">Região: </span><input type="text" name="regiao" id="regiao" maxlength="2" size="1" value="7">
</td>
</tr>

<tr>
<td>Outros estudos realizados:</td>
<td>
<input type="radio" name="outros_estudos" id="outros_estudos" value="especialização">
Especialização
<input type="radio" name="outros_estudos" id="outros_estudos" value="mestrado">
Mestrado
<input type="radio" name="outros_estudos" id="outros_estudos" value="doutorado">
Doutorado
</td>
</tr>

<tr>
<td>Área em que foi realizado o curso anteriormente mencionado</td>
<td><input type="text" name="area_curso" id="area_curso" maxlength="40" size="30"></td>
</tr>

<tr>
<td>Ano em que foi concluído o curso</td>
<td><input type="text" name="ano_curso" id="ano_curso" maxlength="4" size="4"></td>
</tr>

<tr>
<td colspan="2" class="coluna_centralizada">
<input type="submit" name="enviar" value="Confirmar" onClick="return confirma()">
<input type="reset" name="limpar" value="Limpar">
</td>
</tr>

</tbody>
</table>
</div>

</form>

</body>

</html>
