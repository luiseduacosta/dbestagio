<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
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
<td>
{if $usuarios[u].aluno_id}
<a href="../../alunos/exibir/ver_cada.php?aluno_id={$usuarios[u].aluno_id}">{$usuarios[u].aluno}</a>
{else}
—
{/if}
</td>
<td>
{if $usuarios[u].supervisor_id}
<a href="../../supervisores/exibir/ver_cada.php?supervisor_id={$usuarios[u].supervisor_id}">{$usuarios[u].supervisor}</a>
{else}
—
{/if}
</td>
<td>
{if $usuarios[u].professor_id}
<a href="../../professores/exibir/ver_cada.php?professor_id={$usuarios[u].professor_id}">{$usuarios[u].professor}</a>
{else}
—
{/if}
</td>
<td>{$usuarios[u].identificacao}</td>
<td>{if $usuarios[u].ativo_raw == 1}<span style="color:green">Ativo</span>{else}<span style="color:#a00">Inativo</span>{/if}</td>
<td>
<a href="ver_cada.php?id={$usuarios[u].id}">Ver</a> |
<a href="../atualizar/modifica.php?id={$usuarios[u].id}">Editar</a> |
<a href="../cancelar/cancela.php?id={$usuarios[u].id}" onclick="return confirm('Excluir este usuário?');">Excluir</a>
</td>
</tr>
{/section}
{/if}
</tbody>
</table>

<div align="center">
<p><a href="../inserir/formulario.php">Inserir novo usuário</a></p>
</div>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#usuarios').DataTable({
		language: { url: '../../libjs/datatables/pt-BR.json' },
		pageLength: 25,
		lengthMenu: [10, 25, 50, 100],
		order: []
	});
});
</script>
{/literal}

</body>
</html>