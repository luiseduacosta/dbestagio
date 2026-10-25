<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Histórico</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Mural</p>
			<h1>Histórico<span>Histórico das seleções de estágio</span></h1>
		</div>
	</header>

	<section class="table-shell" aria-label="Histórico das seleções de estágio">
	<table id="historico" class="display dt-table">
	<thead>
	<tr>
	<th>Período</th>
	<th>Alunos</th>
	<th>Vagas</th>
	<th>Sem estágio</th>
	</tr>
	</thead>
	<tbody>
	{section name=i loop=$historico}
	<tr>
	<td class="coluna_centralizada">{$historico[i].periodo}</td>
	<td class="coluna_direita"><a href="historico.php?periodo={$historico[i].periodo}">{$historico[i].subtotal}</a></td>
	<td class="coluna_direita"><a href="historico.php?selecao={$historico[i].periodo}">{$historico[i].vagas}</a></td>
	<td class="coluna_centralizada"><a href="historico.php?periodo_sem_estagio={$historico[i].periodo}">{$historico[i].alunos_sem_estagio}</a></td>
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
	$('#historico').DataTable({
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
