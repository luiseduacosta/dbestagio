<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css"> 
<title>Resultado da busca de alunos</title>
</head>

<body>

<div align="center">
<table border="1">
<tbody>

<tr>
<th colspan='2'>Alunos</th>
</tr>

{section name=elemento loop=$alunos}
<tr>
<td>{$alunos[elemento].registro}</td>
<td><a href="../exibir/ver_cada.php?id_aluno={$alunos[elemento].id_aluno}">{$alunos[elemento].nome}</a></td>
</tr>
{/section}

</tbody>
</table>
</div>

</body>

</html>