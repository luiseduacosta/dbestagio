<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>{$titulo}</title>
</head>
<body>

<div align="center">
<h3>{$titulo}</h3>
</div>

<span style="font-size:85%;font-style:italic">Data no formato AAAA-MM-DD (ou vazia para usar hoje).</span>

<form name="form_inscricao" action="{$acao}" method="post">

{if $e_edicao}
<input type="hidden" name="id" value="{$v.id}">
{/if}

<table class="ficha">
<tbody>

<tr>
<td class="coluna_direita"><label for="registro">Aluno *</label></td>
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
<td class="coluna_direita"><label for="muralestagio_id">Oferta do mural *</label></td>
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
<td class="coluna_direita"><label for="periodo">Período *</label></td>
<td>
<select id="periodo" name="periodo" size="1">
{foreach item=p from=$periodos}
<option value="{$p}" {if $p == $v.periodo}selected="selected"{/if}>{$p}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="data">Data da inscrição</label></td>
<td><input type="text" id="data" name="data" size="10" maxlength="10" value="{$v.data}"></td>
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

</body>
</html>