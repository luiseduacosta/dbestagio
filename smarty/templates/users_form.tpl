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

<form name="form_usuario" action="{$acao}" method="post">

{if $v.id}
<input type="hidden" name="id" value="{$v.id}">
{/if}

<table border="1">
<tbody>

<tr>
<td>E-mail *</td>
<td><input type="text" name="email" size="40" maxlength="50" value="{$v.email}"></td>
</tr>

<tr>
<td>Nome *</td>
<td><input type="text" name="nome" size="45" maxlength="128" value="{$v.nome}"></td>
</tr>

<tr>
<td>Categoria (role) *</td>
<td>
<select name="role" size="1">
{foreach key=val item=label from=$opts.roles}
	<option value="{$val}" {if $val == $v.role}selected="selected"{/if}>{$label}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td>Categoria (1-4) *</td>
<td>
<select name="categoria" size="1">
{foreach key=val item=label from=$opts.categorias}
	<option value="{$val}" {if $val == $v.categoria}selected="selected"{/if}>{$label}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td>Identificação</td>
<td><input type="text" name="identificacao" size="12" maxlength="9" value="{$v.identificacao}"></td>
</tr>

<tr>
<td>Situação</td>
<td>
<select name="ativo" size="1">
{foreach key=val item=label from=$opts.ativo_opcoes}
	<option value="{$val}" {if $val == $v.ativo}selected="selected"{/if}>{$label}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td>Senha {if !$e_edicao}*{/if}</td>
<td><input type="password" name="password" size="20" maxlength="72">
{if $e_edicao}<br><span style="font-size:80%;font-style:italic">Deixe em branco para manter a senha atual.</span>{/if}
</td>
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