<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css"> 
<title>Listar áreas</title>
</head>

<body>

<div align="center">
<table border="1">
<caption>Tabela de instituições por área</caption>
<tbody>

<tr>
<th>Área</th>
<th>Quantidade <br> de instituições</th>
<th>Editar <br> área</th>
</tr>

{section name=elemento loop=$areas}
<tr>
<!-- 1a coluna -->
<td>
<a href="instituicoes.php?id_area={$areas[elemento].id_area}">{$areas[elemento].area}</a>
</td>

<!-- 2a coluna -->
<td class="coluna_centralizada">
{$areas[elemento].q_instituicoes}
</td>

<!-- 3a coluna -->
<td class="ultima_linha">
<a href="../atualizar/modifica.php?id_area={$areas[elemento].id_area}">Editar</a>
</td>

<!--
<td class="ultima_linha">
<a href="../cancelar/cancela.php?id_area={$areas[elemento].id_area}" onClick='return elimina()'><button>{$areas[elemento].id_area}</button></a>
</td>
//-->

</tr>
{/section}

<tr class="rodape">
<td class="coluna_centralizada">TOTAL</td>
<td class="coluna_centralizada">{$total}</td>
<td></td>
</tr>

</tbody>
</table>
</div>

</body>

</html>