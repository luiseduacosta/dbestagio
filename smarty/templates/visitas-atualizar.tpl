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
			<p class="eyebrow">Coordenação de Estágio · Visitas</p>
			<h1>{$titulo}<span>Registro de visita às instituições conveniadas</span></h1>
		</div>
	</header>

	<form name="form_visita" action="{$acao}" method="post">

		{if $v.id}
		<input type="hidden" name="id" value="{$v.id}">
		{/if}

		<table class="card-table">
		<tbody>

		<tr>
		<th><label for="instituicao_id">Instituição *</label></th>
		<td>
		<select name="instituicao_id" id="instituicao_id" size="1">
		<option value="">-- Selecione --</option>
		{section name=i loop=$instituicoes}
			<option value="{$instituicoes[i].id}" {if $instituicoes[i].id == $v.instituicao_id}selected="selected"{/if}>
			{$instituicoes[i].instituicao}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<th><label for="professor_id">Professor</label></th>
		<td>
		<select name="professor_id" id="professor_id" size="1">
		<option value="0">-- Nenhum --</option>
		{section name=p loop=$professores}
			<option value="{$professores[p].id}" {if $professores[p].id == $v.professor_id}selected="selected"{/if}>
			{$professores[p].nome}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<th><label for="data">Data *</label></th>
		<td>
		<input type="text" name="data" id="data" size="10" maxlength="10" value="{$v.data}">
		<span class="form-hint">Formato AAAA-MM-DD</span>
		</td>
		</tr>

		<tr>
		<th>Motivo *</th>
		<td>
		{section name=m loop=$motivos}
			<label>
			<input type="radio" name="motivo" value="{$motivos[m]}" {if $motivos[m] == $v.motivo}checked="checked"{/if}> {$motivos[m]}
			</label>
			<br>
		{/section}
		<label><input type="radio" name="motivo" value="Outro" {if $v.motivo == 'Outro'}checked="checked"{/if}> Outro</label>
		</td>
		</tr>

		<tr>
		<th><label for="responsavel">Responsável *</label></th>
		<td><input type="text" name="responsavel" id="responsavel" size="40" maxlength="50" value="{$v.responsavel}"></td>
		</tr>

		<tr>
		<th><label for="avaliacao">Avaliação</label></th>
		<td>
		<select name="avaliacao" id="avaliacao" size="1">
		{section name=a loop=$avaliacoes}
			<option value="{$avaliacoes[a]}" {if $avaliacoes[a] == $v.avaliacao}selected="selected"{/if}>
			{$avaliacoes[a]}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<th><label for="descricao">Descrição</label></th>
		<td><textarea name="descricao" id="descricao" rows="4" cols="60">{$v.descricao}</textarea></td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="submit" name="submit" value="Salvar">
		<input type="button" value="Cancelar" onclick="history.back()">
		</td>
		</tr>

		</tbody>
		</table>

	</form>

	<div class="action-row">
		<a class="btn btn-secondary" href="listar.php">Voltar para a listagem</a>
	</div>

</main>

</body>
</html>
