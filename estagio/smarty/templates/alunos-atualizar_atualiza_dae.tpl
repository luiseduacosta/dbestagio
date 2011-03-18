<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>Aluno modifica estágio</title>
  <meta content="Luis Acosta" name="author">
<style type="text/css">
@import url("../../estagio.css");
</style>

{literal}
<script language="JavaScript" type="text/javascript">
var cep=document.getElementById('cep').value;
alert("cep " + cep);
function verifica_nome() {
    var nome;
    nome=document.getElementById('nome').value;
    if(nome=="") {
		alert("Você precisa informar o seu nome");
		document.atualiza_aluno.nome.focus();
		return false;
    } else {
		return true;
    }
}

function verifica_email() {
    var email;
    email=document.getElementById('email').value;
    if(email=="") {
		alert("É importante o e-mail para nossa comunicação");
		document.atualiza_aluno.email.focus();
		return false;
    } else {
		return true;
    }
}

function verifica_cpf() {
    var cpf;
    cpf=document.getElementById('cpf').value;
    if(cpf=="") {
		alert("Você precisa informar o CPF");
		document.atualiza_aluno.cpf.focus();
		return false;
    } else {
		return true;
    }
}

function verifica_identidade() {
    var identidad;
    identidade=document.getElementById('identidade').value;
    if(identidade=="") {
		alert("Você precisa informar o RG");
		document.atualiza_aluno.identidade.focus();
		return false;
    } else {
		return true;
    }
}

function verifica_orgao() {
    var orgao;
    orgao=document.getElementById('orgao').value;
    if(orgao=="") {
		alert("Você precisa informar o orgão da carteira da identidade");
		document.atualiza_aluno.orgao.focus();
		return false;
    } else {
		return true;
    }
}

function verifica_nascimento() {
    var nascimento;
    nascimento=document.getElementById('nascimento').value;
    if(nascimento=="") {
		alert("Você precisa informar a data de nascimento");
		document.atualiza_aluno.nascimento.focus();
		return false;
    } else {
		return true;
    }
}

function verifica_endereco() {
    var endereco;
    endereco=document.getElementById('endereco').value;
    if(endereco=="") {
		alert("Você precisa informar o seu endereço");
		document.atualiza_aluno.endereco.focus();
		return false;
    } else {
		return true;
    }
}

function verifica_cep() {
    var cep;
    cep=document.getElementById('cep').value;
    if(cep=="") {
		alert("Você precisa informar o CEP");
		document.atualiza_aluno.cep.focus();
		return false;
    } else {
		return true;
    }
}

function verifica_bairro() {
    var bairro;
    bairro=document.getElementById('bairro').value;
    if(bairro=="") {
		alert("Você precisa informar o seu bairro");
		document.atualiza_aluno.bairro.focus();
		return false;
    } else {
		return true;
    }
}

function verifica_municipio() {
    var municipio;
    cep=document.getElementById('municipio').value;
    if(municipio=="") {
		alert("Você precisa informar o seu município");
		document.atualiza_aluno.municipio.focus();
		return false;
    } else {
		return true;
    }
}

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
    	alert("Informe o munícipio da sua residência");
		document.atualiza_aluno.municipio.focus();
		return false;
	}

    return true;
}
</script>
{/literal}

{* Calendario *}
<link rel="stylesheet" type="text/css" href="../../../epoch/epoch_styles.css" />
{literal}
<script type="text/javascript" src="../../../epoch/epoch_classes.js"></script>
<script language="JavaScript" type="text/javascript">

var calendar1;
window.onload = function() {
	calendar1 = new Epoch('nascimento', 'popup', document.getElementById('nascimento'),false);
};

</script>

<script type="text/javascript" src="../../lib/jquery.js"></script>
<script type="text/javascript" src="../../lib/jquery.maskedinput-1.2.1.pack.js"></script>
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

<!--
Modifica dados do aluno
//-->

<div align="center" id="formulario_dae" style="visibility: visible">

<form action="atualiza_dae.php" name="atualiza_aluno" id="atualiza_aluno" method="post">

<table border="1" width="80%">
<caption>Aluna(o): {$aluno_nome} DRE: {$registro}</caption>
<tbody>

<tr>
<td>Registro</td>
<td><input type="text" name="registro" id="registro" size="10" maxlength="10" value="{$registro}"></td>
</tr>

<tr>
<td>Nome</td>
<td><input type="text" name="nome" id="nome" size="30" maxlength="50" value="{$aluno_nome}" onBlur="return verifica_nome();"></td>
</tr>

<tr>
<td>Telefone:</td>
<td>
<input type="text" name="codigo_telefone" id="codigo_telefone" size="2" maxlength="2" value="{$codigo_telefone}">
<input type="text" name="telefone" id="telefone" size="9" maxlength="9" value="{$telefone}">
Celular:
<input type="text" name="codigo_celular" id="codigo_celular" size="2" maxlength="2" value="{$codigo_celular}">
<input type="text" name="celular" id="celular" size="9" maxlength="9" value="{$celular}">
</td>
</tr>

<tr>
<td>E-mail:</td>
<td>
<input type="text" name="email" id="email" size="30" maxlength="50" value="{$email}" onBlur="return verifica_email();">
</td>
</tr>

<tr>
<td>CPF</td>
<td>
<input type="text" name="cpf" id="cpf" maxlength="12" size="12" value='{$cpf}' onBlur="return verifica_cpf();" />
</td>
</tr>

<tr>
<td>
Carteira de identidade:
</td>
<td>
<input type="text" name="identidade" id="identidade" maxlength="15" size="15" value='{$identidade}'  onBlur="return verifica_identidade();"/>
Orgão:
<input type="text" name="orgao" id="orgao" maxlength="10" size="10" value='{$orgao}' onBlur="return verifica_orgao();"/>
</td>
</tr>

<tr>
<td>Data de nascimento</td>
<td>
<input type="text" name="nascimento" id="nascimento" maxlength="10" size="10" value='{$nascimento}' onBlur="return verifica_nascimento();"/>
dd/mm/aaaa
</td>
</tr>

<tr>
<td>Endereço</td>
<td>
<input type="text" name="endereco" id="endereco" maxlength="50" size="30" value='{$endereco}' onBlur="return verifica_endereco();"/>
CEP:
<input type="text" name="cep" id="cep" maxlength="9" size="9" value='{$cep}' onBlur="return verifica_cep();"/>
</td>
</tr>

<tr>
<td>Bairro</td>
<td>
<input type="text" name="bairro" id="bairro" maxlength="30" size="15" value='{$bairro}' onBlur="return verifica_bairro();"/>
Munícipio:
<input type="text" name="municipio" id="municipio" maxlength="30" size="20" value='{$municipio}' onBlur="return verifica_municipio();"/>
</td>
</tr>

<!--
<tr>
<td>Observa&ccedil;&otilde;es</td>
<td>
<textarea name="observacoes" rows="3" cols="60">
{$observacoes}
</textarea>
</td>
</tr>
//-->

<!--
<tr style='background-color:yellow'>
<td colspan=2 style='text-align:center'>Selecione a instituicao para qual solicita o termo de compromisso</td>
</tr>

<tr>
<td>Instituição</td>
<td>
<select name='id_instituicao' size=1>
{section name=i loop=$instituicoes}
<option value={$instituicoes[i].id}>{$instituicoes[i].instituicao}
{/section}
</select>
</td>
</tr>
//-->

<tr>
<td colspan="2" style="text-align: center">
<input type="hidden" name="acao" value="1">
<input type="hidden" name="registro" value="{$registro}">
<input type="submit" name="submit" value="Confirma" onClick="return confirma();">
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>
</html>
