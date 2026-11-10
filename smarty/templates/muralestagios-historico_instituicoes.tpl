<<<<<<< HEAD
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Oferta de vagas da estágio por período</title>
<style type="text/css">
@import url("../estagio.css");
</style>
</head>
<body>

<div align='center'>
<table border='1' summary='Tabela de ofertas de vagas por periodo'>
	<caption>Oferta de vagas de estágio período {$periodo} <br> Total: {$total_vagas}</caption>
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
			<td style='text-align:center;'>{$instituicoes[i].vagas}</td>		
		</tr>
		{/section}
	</tbody>
</table>
</div>

</body>
</html>
=======
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
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa
