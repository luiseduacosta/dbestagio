<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>
</title>
<style>
@import url("formulario_curso.css");
</style>
</head>

<body>

<form name="seleciona_supervisor" action="imprime_formulario.php" method="post">

<table>
<tbody>

<tr>
<td>
<select name="id_supervisor" size="1">
<option value="0">Selecione</option>
{section name=elemento loop=$supervisores}
<option value={$supervisores[elemento].id}>{$supervisores[elemento].num_nome}</option>
{/section}
</select>
</td>

<td>
<input type="submit" name="enviar" value="Confirma">
</td>

</tr>

</tbody>
</table>

</form>

</body>

</html>