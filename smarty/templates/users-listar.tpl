<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../estagio.css" rel="stylesheet" type="text/css">
<title>Listar usuários</title>
</head>
<body>

<div align="center">
<h3>Usuários do sistema</h3>
</div>

<table id="usuarios" class="display">
<thead>
<tr>
<th>E-mail</th>
<th>Nome</th>
<th>Role</th>
<th>Categoria</th>
<th>Aluno</th>
<th>Supervisor</th>
<th>Professor</th>
<th>Identificação</th>
<th>Situação</th>
<th data-orderable="false">Ações</th>
</tr>
</thead>
<tbody>
{foreach from=$usuarios item=u}
<tr>
<td>{$u.email}</td>
<td>{$u.nome}</td>
<td>{$u.role}</td>
<td>{$u.categoria}</td>
<td>
{if $u.aluno_id}
<a href="../alunos/ver_cada.php?aluno_id={$u.aluno_id}">{$u.aluno}</a>
{else}
—
{/if}
</td>
<td>
{if $u.supervisor_id}
<a href="../supervisores/ver_cada.php?supervisor_id={$u.supervisor_id}">{$u.supervisor}</a>
{else}
—
{/if}
</td>
<td>
{if $u.professor_id}
<a href="../professores/ver_cada.php?professor_id={$u.professor_id}">{$u.professor}</a>
{else}
—
{/if}
</td>
<td>{$u.identificacao}</td>
<td>{if $u.ativo_raw == 1}<span style="color:green">Ativo</span>{else}<span style="color:#a00">Inativo</span>{/if}</td>
<td>
<a href="ver_cada.php?id={$u.id}">Ver</a> |
<a href="atualizar.php?id={$u.id}">Editar</a> |
<a href="excluir.php?id={$u.id}" onclick="return confirm('Excluir este usuário?');">Excluir</a>
</td>
</tr>
{/foreach}
</tbody>
</table>

<div align="center">
<p><a href="inserir.php">Inserir novo usuário</a></p>
</div>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#usuarios').DataTable({
		language: { url: '../libjs/datatables/pt-BR.json' },
		pageLength: 25,
		lengthMenu: [10, 25, 50, 100],
		order: []
	});
});
</script>
{/literal}

</body>
</html>
