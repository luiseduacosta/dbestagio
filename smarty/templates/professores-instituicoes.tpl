<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Professores por instituição</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Professores</p>
			<h1>Professores por instituição<span>Instituições vinculadas aos professores através dos estágios</span></h1>
		</div>
	</header>

	<section class="table-shell is-scroll" aria-label="Professores por instituição">

		<table id="instituicoes" class="display dt-table">
		<thead>
		<tr>
		<th>Professor</th>
		<th>Instituição</th>
		</tr>
		</thead>
		<tbody>

		{section name=i loop=$instituicoes}
		<tr>
		<td>{$instituicoes[i].nome}</td>
		<td class="inst-name">{$instituicoes[i].instituicao}</td>
		</tr>
		{/section}

		</tbody>
		</table>

	</section>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#instituicoes').DataTable({
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
