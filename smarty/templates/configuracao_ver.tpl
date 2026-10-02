<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../estagio.css" rel="stylesheet" type="text/css">
<title>Configuração do sistema</title>
</head>
<body>

<div align="center">
<h3>Configuração do sistema</h3>
</div>

<table class="ficha">
<tbody>
{foreach key=col item=linha from=$valores}
<tr>
<th>{$linha.rotulo}</th>
<td>{if $linha.valor === '' || $linha.valor === null}<em>—</em>{else}{$linha.valor}{/if}</td>
</tr>
{/foreach}
</tbody>
</table>

<div align="center">
<p><a href="configuracao_edita.php">Editar configuração</a></p>
</div>

</body>
</html>