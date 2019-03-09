<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Instituiçoes por área</title>
</head>

<body>

<div aling="center">
<table border="1">
<caption>Instituições da área: {$nome_area}</caption>
<tbody>

<tr>
<th><a href="?id_area={$id_area}&ordem=instituicao">Instituição</a></th>
<th><a href="?id_area={$id_area}&ordem=turma">Turma</a></th>
<th>Super- <br> visores</th>
<th><a href="?id_area={$id_area}&ordem=endereco">Endereço</a></th>
<th><a href="?id_area={$id_area}&ordem=telefone">Telefone</a></th>
</tr>

<tr>
</tr>

{section name=elemento loop=$instituicoes}
<tr>
<td><a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$instituicoes[elemento].id}">{$instituicoes[elemento].instituicao}</a></td>
<td><a href="../../alunos/exibir/listar.php?seleciona_instituicao={$instituicoes[elemento].id}&seleciona_periodo={$instituicoes[elemento].turma}">{$instituicoes[elemento].turma}</a></td>
<td class="coluna_centralizada"><a href="../../assistentes/exibir/listar_todos.php?id_instituicao={$instituicoes[elemento].id}">{$instituicoes[elemento].q_supervisores}</a></td>
<td>{$instituicoes[elemento].endereco}</td>
<td>{$instituicoes[elemento].telefone}</td>
</tr>
{/section}

</tbody>
</table>
</div>

</body>

</html>