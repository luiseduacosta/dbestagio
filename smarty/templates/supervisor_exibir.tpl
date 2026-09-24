<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Supervisor</title>
</head>

<body>

<div align="center">
<table border="1">
<caption>Supervisor</caption>
<tbody>

<tr>
<td colspan="2">Id: {$supervisor_id}</td>
</tr>

<tr>
<td>CRESS</td>
<td>{$cress}</td>
</tr>

<tr>
<td>Supervisor</td>
<td>{$nome_supervisor}</td>
</tr>

<tr>
<td>Email</td>
<td>{$email}</td>
</tr>

{section name=elementos loop=$instituicao}
<tr>
<td>Instituição</td>
<td>
<a href="../../instituicoes/exibir/ver_cada.php?instituicao_id={$instituicao[elementos].id}">
{$instituicao[elementos].instituicao|truncate:50}</a>
</td>
</tr>
{/section}

<form name="modifica" action="../atualizar/modifica.php?supervisor_id={$supervisor_id}" method="post">
<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" name="submit" value="Modifica dados">
</td>
</tr>
</form>

</tbody>
</table>
</div>

</body>

</html>