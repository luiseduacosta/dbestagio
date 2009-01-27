<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Listar professores</title>
</head>
<body>

<div align="center">
<table border="1">
<caption>Professores {$periodo}</caption>
<tbody>

<tr>
<th>Id</th>
<th>Nome</th>
</tr>

{assign var="i" value=1}
{section name=i loop=$professores}
<tr>
<td>{$i++}</td>
<td><a href="ver_cada.php?id_professor={$professores[i].id_professor}">{$professores[i].nome}</a></td>
</tr>
{/section}

</tbody>
</table>
</div>

</body>
</html>