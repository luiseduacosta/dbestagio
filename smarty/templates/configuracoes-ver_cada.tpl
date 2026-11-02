<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../estagio.css" type="text/css">
<link rel="stylesheet" href="../estagio-moderno.css">
<title>Configuração do Sistema</title>
</head>
<body id="corpo">
<main class="mural-page">


<div align="center">
<h3>Configuração do Sistema</h3>
</div>

<table class="ficha" border="1" align="center">
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
<p><a href="atualizar.php">Editar configuração</a></p>
</div>

</main>
</body>
</html>
