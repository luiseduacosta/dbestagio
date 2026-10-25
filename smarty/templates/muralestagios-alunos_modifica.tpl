<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Aluno atualiza mural</title>
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
		document.atualiza_aluno.nome.focus();
		return false;
	}

    if (email=="") {
    	alert("É importante o seu e-mail para nossa comunicação");
		document.atualiza_aluno.email.focus();
		return false;
	}

	if (cpf=="") {
    	alert("Favor informar o número de CPF");
		document.atualiza_aluno.cpf.focus();
		return false;
	}

	if (identidade=="") {
    	alert("Informe o seu número de RG");
		document.atualiza_aluno.identidade.focus();
		return false;
	}

	if (orgao=="") {
    	alert("Informe o orgão expedidor do RG");
		document.atualiza_aluno.orgao.focus();
		return false;
	}

	if (endereco=="") {
    	alert("Informe o seu endereço");
		document.atualiza_aluno.endereco.focus();
		return false;
	}

	if (cep=="") {
    	alert("Informe o número de CEP");
		document.atualiza_aluno.cep.focus();
		return false;
	}

	if (bairro=="") {
    	alert("Informe o bairro onde mora");
		document.atualiza_aluno.bairro.focus();
		return false;
	}

	if (municipio=="") {
    	alert("Informe o município da sua residência");
		document.atualiza_aluno.municipio.focus();
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
			<h1>Inscrição em seleção de estágio<span>{$instituicao}</span></h1>
		</div>
	</header>

	{* O histórico do aluno so aparece se o aluno já eh conhecido *}
	{if $aluno == 1}
	<section id="historico_estagios">
		<h2>Histórico dos estágios cursados</h2>
		<div class="table-shell is-scroll">
		<table class="display dt-table">
		<thead>
		<tr>
		<th>Período</th>
		<th>Estágio</th>
		<th>Turno</th>
		<th>Instituição</th>
		<th>Supervisor</th>
		</tr>
		</thead>
		<tbody>
		{section name=item loop=$estagiarios}
		<tr>
		<td class="coluna_centralizada">{$estagiarios[item].periodo}</td>
		<td class="coluna_centralizada">{$estagiarios[item].nivel}</td>
		<td class="coluna_centralizada">{$estagiarios[item].turno}</td>
		<td>{$estagiarios[item].instituicao}</td>
		<td>{$estagiarios[item].supervisor}</td>
		</tr>
		{/section}
		</tbody>
		</table>
		</div>
	</section>
	{/if}

	<div id="aluno_atualiza">

	<form action="mural-alunos_atualiza.php" name="atualiza_aluno" id="atualiza_aluno" method="post">

		{if $sistema_autentica == 1}

		<table class="card-table">
		<tbody>

		<tr>
		<th><label for="nome">Nome</label></th>
		<td><input type="text" name="nome" id="nome" size="50" maxlength="70" value="{$aluno_nome}"></td>
		</tr>

		<tr>
		<th>Registro</th>
		<td>{$registro}</td>
		</tr>

		<tr>
		<th>Telefone</th>
		<td>
		<label for="codigo_telefone">Código</label>
		<input type="text" name="codigo_telefone" id="codigo_telefone" size="2" maxlength="2" value="{$codigo_telefone}">
		<label for="telefone">Telefone</label>
		<input type="text" name="telefone" id="telefone" size="9" maxlength="9" value="{$telefone}">
		</td>
		</tr>

		<tr>
		<th>Celular</th>
		<td>
		<label for="codigo_celular">Código</label>
		<input type="text" name="codigo_celular" id="codigo_celular" size="2" maxlength="2" value="{$codigo_celular}">
		<label for="celular">Celular</label>
		<input type="text" name="celular" id="celular" size="9" maxlength="9" value="{$celular}">
		</td>
		</tr>

		<tr>
		<th><label for="email">E-mail</label></th>
		<td><input type="text" name="email" id="email" size="50" maxlength="50" value="{$email}"></td>
		</tr>

		<tr>
		<th><label for="cpf">CPF</label></th>
		<td><input type="text" name="cpf" id="cpf" size="12" maxlength="12" value="{$cpf}"></td>
		</tr>

		<tr>
		<th>Identidade</th>
		<td>
		<input type="text" name="identidade" id="identidade" size="15" maxlength="15" value="{$identidade}">
		<label for="orgao">Orgão</label>
		<input type="text" name="orgao" id="orgao" size="10" maxlength="10" value="{$orgao}">
		</td>
		</tr>

		<tr>
		<th><label for="nascimento">Nascimento</label></th>
		<td><input type="text" name="nascimento" id="nascimento" size="10" maxlength="10" value="{$nascimento}"></td>
		</tr>

		<tr>
		<th>Endereço</th>
		<td>
		<input type="text" name="endereco" id="endereco" size="30" maxlength="50" value="{$endereco}">
		<label for="cep">CEP</label>
		<input type="text" name="cep" id="cep" size="9" maxlength="9" value="{$cep}">
		</td>
		</tr>

		<tr>
		<th>Bairro</th>
		<td>
		<input type="text" name="bairro" id="bairro" size="15" maxlength="30" value="{$bairro}">
		<label for="municipio">Município</label>
		<input type="text" name="municipio" id="municipio" size="15" maxlength="30" value="{$municipio}">
		</td>
		</tr>

		</tbody>
		</table>

		{/if}

		<table class="card-table">
		<tbody>

		<tr>
		<th>Nome</th>
		<td>{$aluno_nome}</td>
		</tr>

		<tr>
		<th>Registro</th>
		<td>{$registro}</td>
		</tr>

		</tbody>
		</table>

		<input type="hidden" name="sistema_autentica" id="sistema_autentica" value="{$sistema_autentica}">
		<input type="hidden" name="aluno_id" id="aluno_id" value="{$aluno_id}">
		<input type="hidden" name="registro" id="registro" value="{$registro}">
		<input type="hidden" name="muralestagio_id" id="muralestagio_id" value="{$muralestagio_id}">

		<div class="action-row">
		<input type="submit" name="submit" id="submit" value="Confirma" class="btn">
		</div>

	</form>

	</div>

</main>

</body>
</html>
