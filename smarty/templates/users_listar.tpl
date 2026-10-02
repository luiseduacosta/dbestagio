<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Listar usuários</title>
</head>
<body>

<div align="center">
<h3>Usuários do sistema</h3>
</div>

<table class="listagem">
<thead>
<tr>
<th>Email</th>
<th>Nome</th>
<th>Role</th>
<th>Categoria</th>
<th>Identificação</th>
<th>Situação</th>
<th>Ações</th>
</tr>
</thead>
<tbody>
{if $usuarios}
{section name=u loop=$usuarios}
<tr>
<td>{$usuarios[u].email}</td>
<td>{$usuarios[u].nome}</td>
<td>{$usuarios[u].role}</td>
<td>{$usuarios[u].categoria}</td>
<td>{$usuarios[u].identificacao}</td>
<td>{if $usuarios[u].ativo_raw == 1}Ativo{else}Inativo{/if}</td>
<td>
<a href="ver_cada.php?id={$usuarios[u].id}">Ver</a> |
<a href="../atualizar/modifica.php?id={$usuarios[u].id}">Editar</a> |
<a href="../cancelar/cancela.php?id={$usuarios[u].id}" onclick="return confirm('Excluir este usuário?');">Excluir</a>
</td>
</tr>
{/section}
{else}
<tr><td colspan="7" class="coluna_centralizada">Nenhum usuário cadastrado.</td></tr>
{/if}
</tbody>
</table>

<div align="center">
<p><a href="../inserir/formulario.php">Inserir novo usuário</a></p>
</div>

</body>
</html>