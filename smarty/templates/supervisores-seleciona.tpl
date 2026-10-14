<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html> 
<head>
<meta charset="utf-8">
<link href="../estagio.css" rel="stylesheet" type="text/css">
<title>Selecionar Supervisor</title>
{literal}
<script type="text/javascript">
function confirmaExclusao() {
	return confirm("Tem certeza de que deseja excluir este supervisor?");
}
</script>
{/literal}
</head>

<body>

<a href="listar.php">Voltar para a listagem</a><br>

<div align="center">
{if $opcao == "cancela"}
	<h3>Selecionar Supervisor para Excluir</h3>
	<form name="seleciona_supervisor" action="excluir.php" method="post" onsubmit="return confirmaExclusao();">
{elseif $opcao == "atualizar"}
	<h3>Selecionar Supervisor para Modificar</h3>
	<form name="seleciona_supervisor" action="atualizar.php" method="get">
{else}
	<h3>Selecionar Supervisor</h3>
	<form name="seleciona_supervisor" action="ver_cada.php" method="get">
{/if}

<p>
<label for="supervisor_id"><strong>Supervisor:</strong></label>
<select name="supervisor_id" id="supervisor_id" size="1">
{foreach from=$supervisores item=s}
	<option value="{$s.id}">{$s.nome}</option>
{/foreach}
</select>
</p>

<p>
{if $opcao == "cancela"}
	<input type="submit" name="submit" value="Excluir Supervisor">
{else}
	<input type="submit" name="submit" value="Confirmar">
{/if}
</p>

</form>
</div>

</body>
</html>
