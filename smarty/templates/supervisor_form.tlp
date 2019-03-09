<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css"> 
<title>Cadastro do supervisor</title>
</head>

<body>

<form name="supervisor" action="entra_super.php" method="post">

<input type="hidden" name="id_instituicao" value={$id_instituicao}>

<div class="center">
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
<td>Email</td>
<td><input type="text" name="email" id="email" size="50" maxlength="50"></td>
</tr>

<tr>
<td>Selecione supervisor</td>
<td>
<select name="id_supervisor" id="id_supervisor">
<option value="0" selected>Selecione supervisor</option>
{section name=elemento loop=$num_supervisor}
<option value={$num_supervisor[elemento]}>{$nome_supervisor[elemento]}</option>
{/section}
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