<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Aluno atualiza mural</title>
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="author" content="Luis Acosta">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
<style type="text/css">
@import url("../estagio.css");
</style>
<link rel="stylesheet" type="text/css" href="../lib/mygosumenu/1.0/example1.css" />
<script type="text/javascript" src="../lib/mygosumenu/ie5.js"></script>
<script type="text/javascript" src="../lib/mygosumenu/1.0/DropDownMenu1.js"></script>

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
		document.atualiza_aluno.nome.focus();
		return false;
	}

    if(email=="") {
    	alert("É importante o seu e-mail para nossa comunicação");
		document.atualiza_aluno.email.focus();
		return false;
	}

    if(cpf=="") {
    	alert("Favor informar o número de CPF");
		document.atualiza_aluno.cpf.focus();
		return false;
	}

    if(identidade=="") {
    	alert("Informe o seu número de RG");
		document.atualiza_aluno.identidade.focus();
		return false;
	}

    if(orgao=="") {
    	alert("Informe o orgão expedidor do RG");
		document.atualiza_aluno.orgao.focus();
		return false;
	}

    if(endereco=="") {
    	alert("Informe o seu endereço");
		document.atualiza_aluno.endereco.focus();
		return false;
	}

    if(cep=="") {
    	alert("Informe o número de CEP");
		document.atualiza_aluno.cep.focus();
		return false;
	}

    if(bairro=="") {
    	alert("Informe o bairro onde mora");
		document.atualiza_aluno.bairro.focus();
		return false;
	}

    if(municipio=="") {
    	alert("Informe o múnicipio da sua residência");
		document.atualiza_aluno.municipio.focus();
		return false;
	}

    return true;
}
</script>
{/literal}

</head>

<body style="direction: ltr;">

{if $sistema_autentica == 1}
	{include file="mural_menu.tpl"}
{/if}

<h1>Atualiza dados do aluno para inscrição em seleção de estágio na instituição {$instituicao}</h1>

{* O histórico do aluno so aparece se o aluno já eh conhecido *}
{if $aluno == 1}
	<div align="center" id="historico_estagios" style="visibility: visible">
	<table border="1">
	<caption>Histórico dos estágios cursados</caption>
	<tr>
	<th>Período</th>
	<th>Estágio</th>
	<th>Turno</th>
	<th>Instituição</th>
	<th>Supervisor</th>
	</tr>

	{section name=item loop=$estagiarios}
	<tr>
	<td style='text-align:center;'>{$estagiarios[item].periodo}</td>
	<td style='text-align:center;'>{$estagiarios[item].nivel}</td>
	<td style='text-align:center;'>{$estagiarios[item].turno}</td>
	<td>{$estagiarios[item].instituicao}</td>
	<td>{$estagiarios[item].supervisor}</td>
	</tr>
	{/section}
	</table>
	</div>
{/if}

<div align="center" id="aluno_atualiza" style="visibility: visible">

<form action="mural-alunos_atualiza.php" name="atualiza_aluno" id="atualiza_aluno" method="post">

<table border="1" width="80%">
{if $aluno eq 0}
	<caption>Atualizar dados do aluno novo</caption>
{else}
	<caption>Atualizar dados do aluno estagiário</caption>
{/if}
<tbody>

<tr>
<td>Nome</td>
<td><input type="text" name="nome" id="nome" size="50" maxlenght="70" value="{$aluno_nome}"></td>
</tr>

<tr>
<td>Registro</td>
<td>{$registro}</td>
</tr>

<tr>
<td>Telefone</td>
<td>
Código:
<input type="text" name="codigo_telefone" id="codigo_telefone" size="2" maxlenght="2" value="{$codigo_telefone}">
Telefone:
<input type="text" name="telefone" id="telefone" size="9" maxlenght="9" value="{$telefone}">
</td>
</tr>

<tr>
<td>Celular:</td>
<td>
Código:
<input type="text" name="codigo_celular" id="codigo_celular" size="2" maxlenght="2" value="{$codigo_celular}">
Celular:
<input type="text" name="celular" id="celular" size="9" maxlenght="9" value="{$celular}"></td>
</tr>

<tr>
<td>E-mail:</td>
<td><input type="text" name="email" id="email" size="50" maxlenght="50" value="{$email}"></td>
</tr>

<tr>
<td>CPF:</td>
<td><input type="text" name="cpf" id="cpf" size="11" maxlenght="11" value="{$cpf}"></td>
</tr>

<tr>
<td>Identidade:</td>
<td>
<input type="text" name="identidade" id="identidade" size="15" maxlenght="15" value="{$identidade}">
Orgão:
<input type="text" name="orgao" id="orgao" size="10" maxlenght="10" value="{$orgao}">
</td>
</tr>

<tr>
<td>Nascimento:</td>
<td><input type="text" name="nascimento" id="nascimento" size="10" maxlenght="10" value="{$nascimento}"></td>
</tr>

<tr>
<td>Endereço:
</td>
<td>
<input type="text" name="endereco" id="endereco" size="30" maxlenght="50" value="{$endereco}">
CEP:
<input type="text" name="cep" id="cep" size="9" maxlenght="9" value="{$cep}">
</td>
</tr>

<tr>
<td>
Bairro:
</td>
<td>
<input type="text" name="bairro" id="bairro" size="15" maxlenght="30" value="{$bairro}">
Município:
<input type="text" name="municipio" id="municipio" size="15" maxlenght="30" value="{$municipio}">

</td>
</tr>

<tr>
<td colspan="2" style="text-align: center">
<input type="hidden" name="aluno" id="aluno" value="{$aluno}">
<input type="hidden" name="id_aluno" id="id_aluno" value="{$id_aluno}">
<input type="hidden" name="registro" id="registro" value="{$registro}">
<input type="hidden" name="id_instituicao" id="id_instituicao" value="{$id_instituicao}">
<input type="submit" name="submit" id="submit" value="Confirma" onClick="return confirma();" />
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>
</html>