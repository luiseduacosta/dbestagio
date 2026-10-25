<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Oferta de vagas de estágio por período</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Mural</p>
			<h1>Oferta de vagas<span>Período {$periodo}</span></h1>
		</div>
	</header>

	<div class="summary-strip" aria-label="Resumo da oferta">
		<span class="metric"><strong>{$total_vagas}</strong> vagas</span>
		<span class="metric"><strong>{$instituicoes|@count}</strong> instituições</span>
	</div>

	<section class="table-shell" aria-label="Oferta de vagas por instituição">
	<table id="instituicoes" class="display dt-table">
	<thead>
	<tr>
	<th>Instituição</th>
	<th>Vagas</th>
	</tr>
	</thead>
	<tbody>
	{section name=i loop=$instituicoes}
	<tr>
	<td>{$instituicoes[i].instituicao}</td>
	<td class="coluna_centralizada">{$instituicoes[i].vagas}</td>
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
