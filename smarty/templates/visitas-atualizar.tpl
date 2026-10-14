<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>{$titulo}</title>
</head>
<body>

<div align="center">
<h3>{$titulo}</h3>

<form name="form_visita" action="{$acao}" method="post">

{if $v.id}
<input type="hidden" name="id" value="{$v.id}">
{/if}

<table border="1">
<tbody>

<tr>
<td>Instituição *</td>
<td>
<select name="instituicao_id" size="1">
<option value="">-- Selecione --</option>
{section name=i loop=$instituicoes}
	<option value="{$instituicoes[i].id}" {if $instituicoes[i].id == $v.instituicao_id}selected="selected"{/if}>
	{$instituicoes[i].instituicao}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Professor</td>
<td>
<select name="professor_id" size="1">
<option value="0">-- Nenhum --</option>
{section name=p loop=$professores}
	<option value="{$professores[p].id}" {if $professores[p].id == $v.professor_id}selected="selected"{/if}>
	{$professores[p].nome}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Data *</td>
<td><input type="text" name="data" size="10" maxlength="10" value="{$v.data}"> (AAAA-MM-DD)</td>
</tr>

<tr>
<td>Motivo *</td>
<td>
{section name=m loop=$motivos}
	<label>
	<input type="radio" name="motivo" value="{$motivos[m]}" {if $motivos[m] == $v.motivo}checked="checked"{/if}> {$motivos[m]}
	</label>
	<br>
{/section}
<label><input type="radio" name="motivo" value="Outro"> Outro</label>
</td>
</tr>

<tr>
<td>Responsável *</td>
<td><input type="text" name="responsavel" size="40" maxlength="50" value="{$v.responsavel}"></td>
</tr>

<tr>
<td>Avaliação</td>
<td>
<select name="avaliacao" size="1">
{section name=a loop=$avaliacoes}
	<option value="{$avaliacoes[a]}" {if $avaliacoes[a] == $v.avaliacao}selected="selected"{/if}>
	{$avaliacoes[a]}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Descrição</td>
<td><textarea name="descricao" rows="4" cols="60">{$v.descricao}</textarea></td>
</tr>

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" value="Salvar">
<input type="button" value="Cancelar" onclick="history.back()">
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>
</html>