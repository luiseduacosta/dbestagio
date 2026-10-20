<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Insere aluno</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

{literal}
<script language="JavaScript" type="text/javascript" src="../lib/jquery.maskedinput-1.2.1.pack.js"></script>
<script language="JavaScript" type="text/javascript">
$(function() {
	$("#telefone").mask("(99)9999.9999");
 	$("#celular").mask("(99)99999.9999");
	$("#cep").mask("99999-999");
	$("#cpf").mask("999999999-99");
});
</script>
{/literal}

{* Calendario *}
<link rel="stylesheet" type="text/css" href="../../epoch/epoch_styles.css" />
{literal}
<script type="text/javascript" src="../../epoch/epoch_classes.js"></script>
<script language="JavaScript" type="text/javascript">
var calendar1;
window.onload = function() {
	calendar1 = new Epoch('nascimento', 'popup', document.getElementById('nascimento'),false);
}
</script>
{/literal}

</head>

<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Alunos</p>
			<h1>{if $cadastro == 1}Atualizar aluno(a){else}Inserir aluno(a){/if}<span>Cadastro: {$cadastro}</span></h1>
		</div>
	</header>

	<div id="formulario_insere_aluno">

	{if $cadastro == 1}
	<form method="post" action="atualizar.php" name="modifica_aluno" id="modifica_aluno">
	{else}
	<form method="post" action="inserir.php" name="inserir_aluno" id="inserir_aluno">
	{/if}

	<table class="ficha card-table">
		<tbody>

		<tr>
		<td colspan="2">Dados do(a) aluno(a)</td>
		</tr>

		<tr>
		<td><label for="registro">Registro</label></td>
		<td><input type="text" maxlength="9" size="9" name="registro" id="registro" value={$registro}></td>
		</tr>

		<tr>
		<td><label for="nome">Nome</label></td>
		<td><input type="text" maxlength="50" size="50" name="nome" id="nome" value='{$nome}'></td>
		</tr>

		<tr>
		<td><label for="ingresso">Ingresso</label></td>
		<td><input type="text" maxlength="6" size="6" name="ingresso" id="ingresso" value='{$ingresso}'> Formato: AAAA-S (ex.: 2026-1)</td>
		</tr>

		<tr>
		<td>Telefone</td>
		<td>
		<input type="text" maxlength="2" size="2" name="codigo_telefone" value={$codigo_telefone}>
		<input type="text" maxlength="9" size="9" id="telefone" name="telefone" value={$telefone}>
		</td>
		</tr>

		<tr>
		<td>Celular</td>
		<td>
		<input type="text" maxlength="2" size="2" name="codigo_celular" value={$codigo_celular}>
		<input type="text" maxlength="9" size="9" id="celular" name="celular" value={$celular}>
		</td>
		</tr>

		<tr>
		<td><label for="email">E-mail</label></td>
		<td><input type="text" maxlength="50" size="50" id="email" name="email" value='{$email}'></td>
		</tr>

		<tr>
		<td><label for="cpf">CPF</label></td>
		<td><input type="text" maxlength="12" size="12" id="cpf" name="cpf" value='{$cpf}' /></td>
		</tr>

		<tr>
		<td>Carteira de identidade</td>
		<td>
		<input type="text" maxlength="15" size="15" id="identidade" name="identidade" value='{$identidade}' />
		&nbsp;Orgão:
		<input type="text" maxlength="10" size="10" id="orgao" name="orgao" value='{$orgao}' />
		</td>
		</tr>

		<tr>
		<td>Data de nascimento</td>
		<td>
		<input id="nascimento" type="text" maxlength="10" size="10" name="nascimento" value='{$nascimento}' />
		dd/mm/aaaa
		</td>
		</tr>

		<tr>
		<td>Endereço</td>
		<td>
		<input type="text" maxlength="50" size="30" id="endereco" name="endereco" value='{$endereco}' />
		&nbsp;CEP: <input type="text" maxlength="9" size="9" id="cep" name="cep" value='{$cep}' />
		</td>
		</tr>

		<tr>
		<td>Município / Bairro</td>
		<td>
		<input type="text" maxlength="30" size="20" name="municipio" id="municipio" value='{$municipio}' />
		&nbsp;Bairro:
		<input type="text" maxlength="30" size="20" name="bairro" id="bairro" value='{$bairro}' />
		</td>
		</tr>

		<tr>
		<td><label for="turno_id">Turno</label></td>
		<td>
		<select name="turno_id" id="turno_id" size="1">
		<option value="0">Selecione turno</option>
		{section name=t loop=$turnos}
		<option value="{$turnos[t].id}" {if $turnos[t].id == $turno_id}selected="selected"{/if}>{$turnos[t].turno}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		{if $cadastro == 1}
		    <input type="hidden" name="aluno_id" value={$aluno_id}>
		    <input type="hidden" name="origem" value={$origem}>
		    <input type="hidden" name="valorcadastro" value={$cadastro}>
		    <button type="submit" name="atualiza" class="btn">Clique aqui para atualizar</button>
		{else}
		    <input type="hidden" name="origem" value={$origem}>
		    <input type="hidden" name="valorcadastro" value={$cadastro}>
		    <button type="submit" name="insere" class="btn">Clique aqui para inserir</button>
		{/if}
		</td>
		</tr>

		</tbody>
	</table>

	</form>

	</div>

</main>

</body>
</html>
