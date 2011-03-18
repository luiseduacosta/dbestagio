<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Insere aluno para seleção de estágio</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="Luis Acosta" content="author">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
<style type="text/css">
@import url("../estagio.css");
</style>
{literal}
<script language="JavaScript" type="text/javascript">

function confirma() {

    var nome, email, cpf, identidade, orgao, nascimento, endereco, cep, bairro, municipio;

    nome=document.getElementById('nome').value;
    email=document.getElementById('email').value;
    cpf=document.getElementById('cpf').value;
	identidade=document.getElementById('identidade').value;
	orgao=document.getElementById('orgao').value;
	nascimento=document.getElementById('nascimento').value;
	endereco=document.getElementById('endereco').value;
	cep=document.getElementById('cep').value;
 	bairro=document.getElementById('bairro').value;
	municipio=document.getElementById('municipio').value;

    if(nome=="") {
    	alert("Você precisa informar o seu nome");
		document.inserir_aluno.nome.focus();
		return false;
	}

    if(email=="") {
    	alert("É importante o seu e-mail para nossa comunicação");
		document.inserir_aluno.email.focus();
		return false;
	}

    if(cpf=="") {
    	alert("Favor informar o número de CPF");
		document.inserir_aluno.cpf.focus();
		return false;
	}

    if(identidade=="") {
    	alert("Informe o seu número de RG");
		document.inserir_aluno.identidade.focus();
		return false;
	}

    if(orgao=="") {
    	alert("Informe o orgão expedidor do RG");
		document.inserir_aluno.orgao.focus();
		return false;
	}

    if(endereco=="") {
    	alert("Informe o seu endereço");
		document.inserir_aluno.endereco.focus();
		return false;
	}

    if(cep=="") {
    	alert("Informe o número de CEP");
		document.inserir_aluno.cep.focus();
		return false;
	}

    if(bairro=="") {
    	alert("Informe o bairro onde mora");
		document.inserir_aluno.bairro.focus();
		return false;
	}

    if(municipio=="") {
    	alert("Informe o município da sua residência");
		document.inserir_aluno.municipio.focus();
		return false;
	}

    return true;
}

</script>
{/literal}

{* Calendario *}
<link rel="stylesheet" type="text/css" href="../../epoch/epoch_styles.css" />
<script type="text/javascript" src="../../epoch/epoch_classes.js"></script>
{literal}
<script language="JavaScript" type="text/javascript">
var calendar1;
window.onload = function() {
	calendar1 = new Epoch('nascimento', 'popup', document.getElementById('nascimento'),false);
}
</script>

<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jquery.maskedinput-1.2.1.pack.js"></script>
<script type="text/javascript">
$(function() {
	$("#telefone").mask("9999.9999");
 	$("#celular").mask("9999.9999");
	$("#cep").mask("99999-999");
	$("#cpf").mask("999999999-99");
});
</script>

{/literal}

</head>

<body style="direction: ltr;">

<div align="center" id="formulario_insere_aluno" style="visibility: visible">

<form method="post" action="cadastro.php" name="inserir_aluno" id="inserir_aluno">

<table border="1">
<caption>Inserir aluno novo para seleção de estágio em {$instituicao}</caption>
<tbody>

<tr>
<td width="15%">Registro:</td>
<td>{$registro}</td>
</tr>

<tr>
<td>Nome:</td>
<td><input type="text" maxlength="50" size="50" id="nome" name="nome" /></td>
</tr>

<tr>
<td>Telefone</td>
<td>
Código:
<input type="text" maxlength="2" size="2" name="codigo_telefone" value="21" />
<input type="text" maxlength="9" size="9" id="telefone" name="telefone" />
<b>Celular</b>
Código:
<input type="text" maxlength="2" size="2" name="codigo_celular" value="21" />
<input type="text" maxlength="9" size="9" id="celular" name="celular" />
</td>
</tr>

<tr>
<td>E-mail</td>
<td><input type="text" maxlength="40" size="40" id="email" name="email" /></td>
</tr>

<tr>
<td>CPF</td>
<td>
<input type="text" maxlength="12" size="12" id="cpf" name="cpf" />
</td>
</tr>

<tr>
<td>
Carteira de identidade:
</td>
<td>
<input type="text" maxlength="15" size="15" id="identidade" name="identidade" />
Orgão:
<input type="text" maxlength="10" size="10" id="orgao" name="orgao" />
</td>
</tr>

<tr>
<td>Data de nascimento</td>
<td>
<input id="nascimento" type="text" maxlength="10" size="10" id="nascimento" name="nascimento" />
</td>
</tr>

<tr>
<td>Endereço</td>
<td>
<input type="text" maxlength="50" size="50" id="endereco" name="endereco" />
CEP: <input type="text" maxlength="9" size="9" id="cep" name="cep" />
</td>
</tr>

<tr>
<td>Bairro</td>
<td>
<input type="text" maxlength="30" size="20" id="bairro" name="bairro" />
Município: 
<input type="text" maxlength="30" size="20" id="municipio" name="municipio" />
</td>
</tr>

<tr>
<td colspan="2" class="coluna_centralizada">
<input type="hidden" name="id_instituicao" id="id_instituicao" value="{$id_instituicao}" />
<input type="hidden" name="registro" id="registro" value="{$registro}" />
<input type="submit" name="submit" value="Confirma" onClick="return confirma();" />
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>
</html>
