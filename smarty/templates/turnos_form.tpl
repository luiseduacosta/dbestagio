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

<form name="form_turno" action="{$acao}" method="post">

{if $v.id}
<input type="hidden" name="id" value="{$v.id}">
{/if}

<table border="1">
<tbody>

<tr>
<td>Turno *</td>
<td><input type="text" name="turno" size="30" value="{$v.turno}"></td>
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
