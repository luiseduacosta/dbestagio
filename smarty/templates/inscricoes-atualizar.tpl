<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$titulo}</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Inscrições</p>
			<h1>{$titulo}<span>{if $e_edicao}Alteração de inscrição existente{else}Nova inscrição em oferta do mural{/if}</span></h1>
		</div>
	</header>

	<form name="form_inscricao" action="{$acao}" method="post">

	{if $e_edicao}
	<input type="hidden" name="id" value="{$v.id}">
	{/if}

	<table class="card-table">
	<tbody>

		<tr>
		<th><label for="registro">Aluno *</label></th>
		<td>
		{if $e_aluno}
			{$v.registro}
			<input type="hidden" id="registro" name="registro" value="{$v.registro}">
		{else}
			<select id="registro" name="registro" size="1">
			<option value="">Selecione o aluno</option>
			{foreach item=al from=$alunos}
			<option value="{$al.registro}" {if $al.registro == $v.registro}selected="selected"{/if}>{$al.registro} - {$al.nome}</option>
			{/foreach}
			</select>
		{/if}
		</td>
		</tr>

		<tr>
		<th><label for="muralestagio_id">Oferta do mural *</label></th>
		<td>
			<select id="muralestagio_id" name="muralestagio_id" size="1">
			<option value="0">Selecione a oferta</option>
			{foreach item=oferta from=$ofertas}
			<option value="{$oferta.id}" {if $oferta.id == $v.muralestagio_id}selected="selected"{/if}>({$oferta.periodo}) {$oferta.instituicao}</option>
			{/foreach}
			</select>
		</td>
		</tr>

		<tr>
		<th><label for="periodo">Período *</label></th>
		<td>
			<select id="periodo" name="periodo" size="1">
			{foreach item=p from=$periodos}
			<option value="{$p}" {if $p == $v.periodo}selected="selected"{/if}>{$p}</option>
			{/foreach}
			</select>
		</td>
		</tr>

		<tr>
		<th><label for="data">Data da inscrição</label></th>
		<td>
			<input type="text" id="data" name="data" size="10" maxlength="10" value="{$v.data}">
			<span class="form-hint">Formato AAAA-MM-DD. Em branco usa a data de hoje.</span>
		</td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
			<input type="submit" value="Salvar" class="btn">
			<a class="btn btn-secondary" href="listar.php?periodo={$v.periodo}">Cancelar</a>
		</td>
		</tr>

	</tbody>
	</table>

	</form>

</main>

</body>
</html>
