<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Turnos</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · ESS/UFRJ</p>
			<h1>Turnos<span>Turnos de alunos cadastrados</span></h1>
		</div>
	</header>

	<section class="table-shell" aria-label="Turnos">

		<table id="turnos" class="display dt-table">
		<thead>
		<tr>
		<th>Id</th>
		<th>Turno</th>
		<th>Alunos</th>
		<th data-orderable="false">Ações</th>
		</tr>
		</thead>
		<tbody>

		{section name=i loop=$turnos}
		<tr>
		<td class="coluna_direita">{$turnos[i].id}</td>
		<td class="inst-name"><a href="ver_cada.php?id={$turnos[i].id}">{$turnos[i].turno}</a></td>
		<td class="coluna_direita">{$turnos[i].num_alunos}</td>
		<td>
			<span class="action-links">
			<a href="ver_cada.php?id={$turnos[i].id}">Ver</a>
			<a href="atualizar.php?id={$turnos[i].id}">Editar</a>
			<a class="is-danger" href="excluir.php?id={$turnos[i].id}" onclick="return confirm('Excluir este turno?');">Excluir</a>
			</span>
		</td>
		</tr>
		{/section}

		</tbody>
		</table>

	</section>

	<div class="action-row">
		<a class="action-btn" href="inserir.php">Inserir novo turno</a>
	</div>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#turnos').DataTable({
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
