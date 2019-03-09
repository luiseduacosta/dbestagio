<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css"> 
<title>Listar áreas</title>
</head>

<body>

<div align="center">
<table border="1">
<caption>Tabela de professores por área</caption>
<tbody>

<tr>
<th><a href=?ordem=areas_estagio.area>Área</a></th>
<th><a href=?ordem=professores.nome>Professor</a></th>
<th><a href=?ordem=min(estagiarios.periodo)>Período início</a></th>
<th><a href=?ordem=max(estagiarios.periodo)>Período último</a></th>
</tr>

{section name=elemento loop=$areas}
<tr>
<!-- 1a coluna -->
<td>
{$areas[elemento].area}
</td>

<!-- 2a coluna -->
<td>
<a href='../../professores/exibir/ver_cada.php?id_professor={$areas[elemento].id_professor}&id_area={$areas[elemento].area_id}'>{$areas[elemento].nome}</a>
</td>

<!-- 3a coluna -->
<td style='text-align:center'>
{$areas[elemento].min_periodo}
</td>

<!-- 4a coluna -->
<td style='text-align:center'>
{$areas[elemento].max_periodo}
</td>

</tr>
{/section}

</tbody>
</table>
</div>

</body>

</html>