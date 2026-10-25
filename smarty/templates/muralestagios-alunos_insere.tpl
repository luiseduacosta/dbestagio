<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Insere aluno para seleção de estágio</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

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

	if (nome=="") {
    		alert("Você precisa informar o seu nome");
		document.inserir_aluno.nome.focus();
		return false;
	}

	if (email=="") {
    		alert("É importante o seu e-mail para nossa comunicação");
		document.inserir_aluno.email.focus();
		return false;
	}

	if (cpf=="") {
    		alert("Favor informar o número de CPF");
		document.inserir_aluno.cpf.focus();
		return false;
	}

	if (identidade=="") {
    		alert("Informe o seu número de RG");
		document.inserir_aluno.identidade.focus();
		return false;
	}

	if (orgao=="") {
    		alert("Informe o orgão expedidor do RG");
		document.inserir_aluno.orgao.focus();
		return false;
	}

	if(endereco=="") {
    		alert("Informe o seu endereço");
		document.inserir_aluno.endereco.focus();
		return false;
	}

	if (cep=="") {
    		alert("Informe o número de CEP");
		document.inserir_aluno.cep.focus();
		return false;
	}

	if (bairro=="") {
    		alert("Informe o bairro onde mora");
		document.inserir_aluno.bairro.focus();
		return false;
	}

	if (municipio=="") {
    		alert("Informe o município da sua residência");
		document.inserir_aluno.municipio.focus();
		return false;
	}

    return true;
}

</script>
{/literal}

</head>

<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Mural</p>
			<h1>Nova inscrição<span>Seleção de estágio em {$instituicao}</span></h1>
		</div>
	</header>

	<div id="formulario_insere_aluno">

	<form method="post" action="mural-aluno_inscricao.php" name="inserir_aluno" id="inserir_aluno">

		<table class="card-table">
		<tbody>

		<tr>
		<th>Registro</th>
		<td>{$registro}</td>
		</tr>

		<tr>
		<th><label for="nome">Nome</label></th>
		<td><input type="text" maxlength="50" size="50" id="nome" name="nome" /></td>
		</tr>

		<tr>
		<th>Telefone</th>
		<td>
		<label for="telefone">Código</label>
		<input type="text" maxlength="2" size="2" name="codigo_telefone" value="21" />
		<input type="text" maxlength="9" size="9" id="telefone" name="telefone" />
		<label for="celular">Celular · Código</label>
		<input type="text" maxlength="2" size="2" name="codigo_celular" value="21" />
		<input type="text" maxlength="9" size="9" id="celular" name="celular" />
		</td>
		</tr>

		<tr>
		<th><label for="email">E-mail</label></th>
		<td><input type="text" maxlength="40" size="40" id="email" name="email" /></td>
		</tr>

		<tr>
		<th><label for="cpf">CPF</label></th>
		<td><input type="text" maxlength="12" size="12" id="cpf" name="cpf" /></td>
		</tr>

		<tr>
		<th>Carteira de identidade</th>
		<td>
		<input type="text" maxlength="15" size="15" id="identidade" name="identidade" />
		<label for="orgao">Orgão</label>
		<input type="text" maxlength="10" size="10" id="orgao" name="orgao" />
		</td>
		</tr>

		<tr>
		<th><label for="nascimento">Data de nascimento</label></th>
		<td><input type="text" maxlength="10" size="10" id="nascimento" name="nascimento" /></td>
		</tr>

		<tr>
		<th>Endereço</th>
		<td>
		<input type="text" maxlength="50" size="50" id="endereco" name="endereco" />
		<label for="cep">CEP</label>
		<input type="text" maxlength="9" size="9" id="cep" name="cep" />
		</td>
		</tr>

		<tr>
		<th>Bairro</th>
		<td>
		<input type="text" maxlength="30" size="20" id="bairro" name="bairro" />
		<label for="municipio">Município</label>
		<input type="text" maxlength="30" size="20" id="municipio" name="municipio" />
		</td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="hidden" name="muralestagio_id" id="muralestagio_id" value="{$muralestagio_id}" />
		<input type="hidden" name="registro" id="registro" value="{$registro}" />
		<input type="submit" name="submit" value="Confirma" onClick="return confirma();" />
		</td>
		</tr>

		</tbody>
		</table>

	</form>

	</div>

</main>

</body>
</html>
