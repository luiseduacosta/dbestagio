<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../estagio.css" rel="stylesheet" type="text/css"> 
<title>Busca de Supervisores</title>
</head>

<body>

<p>
<a href="listar.php">Voltar para a listagem</a>
</p>

<div align="center">
<h3>Buscar Supervisores</h3>

<form name="busca_supervisores" action="buscar.php" method="get">
<p>
<label for="palavra">Digite um nome para buscar:</label>
<input type="text" name="palavra" id="palavra" size="30" value="{$palavra|escape}">
<input type="submit" value="Buscar">
</p>
</form>

{if $palavra}
<h4>Resultado da busca por: "{$palavra|escape}"</h4>

{if $supervisores}
<table border="1">
<thead>
<tr>
<th>#</th>
<th>CRESS</th>
<th>Supervisor</th>
<th>E-mail</th>
<th>Telefone</th>
<th>Celular</th>
</tr>
</thead>
<tbody>
{foreach from=$supervisores item=s key=idx}
<tr>
<td class="coluna_direita">{$idx+1}</td>
<td class="coluna_direita">{$s.cress}</td>
<td><a href="ver_cada.php?supervisor_id={$s.supervisor_id}">{$s.nome_supervisor}</a></td>
<td>{if $s.email}<a href="mailto:{$s.email}">{$s.email}</a>{else}—{/if}</td>
<td>{$s.telefone}</td>
<td>{$s.celular}</td>
</tr>
{/foreach}
</tbody>
</table>
{else}
<p>Nenhum supervisor encontrado com o termo "{$palavra|escape}".</p>
{/if}

{/if}

</div>

</body>
</html>
