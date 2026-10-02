<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Listar visitas</title>
</head>
<body>

<div align="center">
<h3>Visitas registradas</h3>
</div>

<table class="listagem">
<thead>
<tr>
<th>Data</th>
<th>Instituição</th>
<th>Professor</th>
<th>Motivo</th>
<th>Responsável</th>
<th>Avaliação</th>
<th>Ações</th>
</tr>
</thead>
<tbody>
{if $visitas}
{section name=v loop=$visitas}
<tr>
<td>{$visitas[v].data}</td>
<td>{$visitas[v].nome_instituicao}</td>
<td>{$visitas[v].nome_professor}</td>
<td>{$visitas[v].motivo}</td>
<td>{$visitas[v].responsavel}</td>
<td>{$visitas[v].avaliacao}</td>
<td>
<a href="ver_cada.php?id={$visitas[v].id}">Ver</a> |
<a href="../atualizar/modifica.php?id={$visitas[v].id}">Editar</a> |
<a href="../cancelar/cancela.php?id={$visitas[v].id}">Excluir</a>
</td>
</tr>
{/section}
{else}
<tr><td colspan="7" class="coluna_centralizada">Nenhuma visita registrada.</td></tr>
{/if}
</tbody>
</table>

<div align="center">
<p><a href="../inserir/formulario.php">Inserir nova visita</a></p>
</div>

</body>
</html>