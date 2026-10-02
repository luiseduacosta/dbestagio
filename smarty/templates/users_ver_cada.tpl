<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Ver usuário</title>
</head>
<body>

<div align="center">
<h3>Dados do usuário</h3>
</div>

<table class="ficha">
<tbody>
<tr><th>E-mail</th><td>{$u.email}</td></tr>
<tr><th>Nome</th><td>{$u.nome}</td></tr>
<tr><th>Role</th><td>{$u.role_texto}</td></tr>
<tr><th>Categoria</th><td>{$u.categoria_texto}</td></tr>
<tr><th>Identificação</th><td>{$u.identificacao}</td></tr>
<tr><th>Situação</th><td>{if $u.ativo == 1}Ativo{else}Inativo{/if}</td></tr>
{if $u.aluno}    <tr><th>Aluno</th><td>{$u.aluno}</td></tr>{/if}
{if $u.supervisor}<tr><th>Supervisor</th><td>{$u.supervisor}</td></tr>{/if}
{if $u.professor} <tr><th>Professor</th><td>{$u.professor}</td></tr>{/if}
</tbody>
</table>

<div align="center">
<p>
<a href="../atualizar/modifica.php?id={$u.id}">Editar</a> |
<a href="javascript:history.back()">Voltar</a> |
<a href="../cancelar/cancela.php?id={$u.id}" onclick="return confirm('Excluir este usuário?');">Excluir</a>
</p>
</div>

</body>
</html>