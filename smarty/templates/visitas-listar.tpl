<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Visitas registradas</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Visitas</p>
			<h1>Visitas registradas<span>Visitas às instituições conveniadas</span></h1>
		</div>
	</header>

	<section class="table-shell is-scroll" aria-label="Visitas registradas">

		<table id="visitas" class="display dt-table">
		<caption>Visitas registradas</caption>

		<thead>
		<tr>
		<th>Data</th>
		<th>Instituição</th>
		<th>Professor</th>
		<th>Motivo</th>
		<th>Responsável</th>
		<th>Avaliação</th>
		<th data-orderable="false">Ações</th>
		</tr>
		</thead>

		<tbody>
		{if $visitas}
		{section name=v loop=$visitas}
		<tr>
		<td class="coluna_centralizada">{$visitas[v].data}</td>
		<td class="inst-name">{$visitas[v].nome_instituicao}</td>
		<td>{$visitas[v].nome_professor}</td>
		<td>{$visitas[v].motivo}</td>
		<td>{$visitas[v].responsavel}</td>
		<td class="coluna_centralizada">{$visitas[v].avaliacao}</td>
		<td class="action-links">
			<a href="ver_cada.php?id={$visitas[v].id}">Ver</a>
			<a href="atualizar.php?id={$visitas[v].id}">Editar</a>
			<a class="is-danger" href="excluir.php?id={$visitas[v].id}" onclick="return confirm('Excluir esta visita?');">Excluir</a>
		</td>
		</tr>
		{/section}
		{else}
		<tr><td colspan="7" class="coluna_centralizada">Nenhuma visita registrada.</td></tr>
		{/if}
		</tbody>
		</table>

	</section>

	<div class="action-row">
		<a class="btn" href="inserir.php">Inserir nova visita</a>
	</div>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#visitas').DataTable({
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
