<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Professores</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

{literal}
<script type="text/javascript">
function carrega_tabela() {
	busca=document.getElementById('busca').value;
	status=document.getElementById('status').value;
	window.location="listar.php?busca=" + encodeURIComponent(busca) + "&status=" + status;
	return false;
}
</script>
{/literal}

</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · ESS/UFRJ</p>
			<h1>Professores<span>Supervisores acadêmicos de estágio</span></h1>
		</div>

		<form class="periodo-form" name="filtros" onsubmit="return carrega_tabela();">
			<label for="busca">Busca</label>
			<input type="text" id="busca" name="busca" value="{$busca}" onkeyup="if (event.keyCode == 13) return carrega_tabela();">
			<label for="status">Status</label>
			<select id="status" name="status" onChange="return carrega_tabela();">
			{foreach item=s key=k from=$statuses}
			<option value="{$k}" {if $k == $status}selected{/if}>{$s}</option>
			{/foreach}
			</select>
			<button type="submit" class="btn btn-sm">Filtrar</button>
		</form>
	</header>

	<section class="table-shell" aria-label="Professores">
		<table id="professores" class="display dt-table">
		<thead>
		<tr>
		<th>Nome</th>
		<th>Departamento</th>
		<th>E-mail</th>
		<th>Telefone</th>
		<th>Celular</th>
		<th>Status</th>
		<th>Nº estágios</th>
		<th data-orderable="false">Ações</th>
		</tr>
		</thead>
		<tbody>

		{foreach item=p from=$professores}
		<tr>
		<td>
		<a href="ver_cada.php?professor_id={$p.id}">{$p.nome}</a>
		</td>
		<td>{$p.departamento}</td>
		<td>{$p.email}</td>
		<td class="coluna_centralizada">{$p.telefone}</td>
		<td class="coluna_centralizada">{$p.celular}</td>
		<td>{$p.status}</td>
		<td class="coluna_centralizada">{$p.num_estagios}</td>
		<td>
		<span class="action-links">
		<a href="ver_cada.php?professor_id={$p.id}">Ver</a>
		{if $isAdmin}
		<a href="atualizar.php?professor_id={$p.id}">Editar</a>
		<a class="is-danger" href="excluir.php?professor_id={$p.id}" onclick="return confirm('Excluir este professor?');">Excluir</a>
		{/if}
		</span>
		</td>
		</tr>
		{/foreach}

		</tbody>
		</table>
	</section>

	<div class="action-row">
		<a class="action-btn" href="inserir.php">Novo professor</a>
	</div>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#professores').DataTable({
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
