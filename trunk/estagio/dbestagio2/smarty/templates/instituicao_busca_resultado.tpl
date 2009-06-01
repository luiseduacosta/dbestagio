<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css"> 
<title>Resultado da busca de instituições</title>
</head>

<body>

<div align="center">
<table border="1">
<caption>Instituições</caption>
<tbody>

{section name=elemento loop=$instituicao}
<tr>
<td><a href="../exibir/ver_cada.php?id_instituicao={$instituicao[elemento].id}">{$instituicao[elemento].instituicao}</a></td>
</tr>
{/section}

</tbody>
</table>
</div>

</body>

</html>