<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Ver turno</title>
</head>
<body>

<div align="center">
<h3>Dados do turno</h3>
</div>

<table class="ficha">
<tbody>
<tr><th>Id</th><td>{$t.id}</td></tr>
<tr><th>Turno</th><td>{$t.turno}</td></tr>
<tr><th>Alunos</th><td>{$num_alunos}</td></tr>
</tbody>
</table>

{if $alunos}
<div align="center">
<h4>Alunos neste turno</h4>
</div>

<table class="listagem">
<thead>
<tr>
<th>Registro</th>
<th>Nome</th>
<th>Ingresso</th>
</tr>
</thead>
<tbody>
{section name=i loop=$alunos}
<tr>
<td class="coluna_direita">{$alunos[i].registro}</td>
<td><a href="../../alunos/exibir/ver_cada.php?aluno_id={$alunos[i].id}">{$alunos[i].nome}</a></td>
<td class="coluna_centralizada">{$alunos[i].ingresso}</td>
</tr>
{/section}
</tbody>
</table>
{/if}

<div align="center">
<p>
<a href="../atualizar/modifica.php?id={$t.id}">Editar</a> |
<a href="listar.php">Voltar</a>
</p>
</div>

</body>
</html>
