<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Listar usuários</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Administração · ESS/UFRJ</p>
			<h1>Usuários do sistema<span>Contas de acesso ao sistema de estágio</span></h1>
		</div>
	</header>

	<section class="table-shell" aria-label="Usuários do sistema">
		<table id="usuarios" class="display dt-table">
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
			<td>{if $u.ativo_raw == 1}<span class="convenio-badge sim">Ativo</span>{else}<span class="convenio-badge nao">Inativo</span>{/if}</td>
			<td>
			<span class="action-links">
			<a href="ver_cada.php?id={$u.id}">Ver</a>
			<a href="atualizar.php?id={$u.id}">Editar</a>
			<a class="is-danger" href="excluir.php?id={$u.id}" onclick="return confirm('Excluir este usuário?');">Excluir</a>
			</span>
			</td>
			</tr>
			{/foreach}
			</tbody>
		</table>
	</section>

	<div class="action-row">
		<a class="action-btn" href="inserir.php">Inserir novo usuário</a>
	</div>

</main>

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
