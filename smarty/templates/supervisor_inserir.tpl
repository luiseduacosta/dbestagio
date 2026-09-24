<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css"> 
<title>Cadastro do supervisor</title>
</head>

<body>

<form name="supervisor" action="inserir.php" method="post">

<div align="center">
<table>
<caption>Supervisor</caption>
<tbody>

<tr>
<td>Cress</td>
<td><input type="text" name="cress" id="cress" size="10" maxlength="10"></td>
</tr>

<tr>
<td>Nome</td>
<td><input type="text" name="nome" id="nome" size="50" maxlength="50"></td>
</tr>

<tr>
<td>Selecione</td>
<td>
<select name="supervisor_id" id="supervisor_id">
<option value="0" selected>Selecione supervisor</option>
{section name=elemento loop=$num_supervisor}
<option 
value={$num_supervisor[elemento]}>{$nome_supervisor[elemento]|truncate:50}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Email</td>
<td><input type="text" name="email" id="email" size="50" maxlength="50"></td>
</tr>

<tr>
<td>Instituição</td>
<td>
<select name="instituicao_id" id="instituicao_id">
{html_options values=$num_instituicao selected=$instituicao_id output=$instituicao|truncate:50}
</select>
</td>
</tr>

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" name="confirmar" value="Confirmar">
</td>
</tr>

</tbody>
</table>
</div>

</form>

</body>

</html>