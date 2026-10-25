<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ver Área</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · ESS/UFRJ</p>
			<h1>Área<span>Visualizando área: {$area.area}</span></h1>
		</div>
	</header>

	<table id="navegacao">
	<tbody>
	<tr>

	{if $primeiro_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="area_id" value="{$primeiro_id}">
	<input type="submit" value="Primeiro">
	</form>
	</td>
	{/if}

	{if $menos_10_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="area_id" value="{$menos_10_id}">
	<input type="submit" value="-10">
	</form>
	</td>
	{/if}

	{if $anterior_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="area_id" value="{$anterior_id}">
	<input type="submit" value="Retroceder">
	</form>
	</td>
	{/if}

	{if $proximo_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="area_id" value="{$proximo_id}">
	<input type="submit" value="Avançar">
	</form>
	</td>
	{/if}

	{if $mais_10_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="area_id" value="{$mais_10_id}">
	<input type="submit" value="+10">
	</form>
	</td>
	{/if}

	{if $ultimo_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="area_id" value="{$ultimo_id}">
	<input type="submit" value="Último">
	</form>
	</td>
	{/if}

	<td>
	<form action="inserir.php" method="get">
	<input type="submit" value="Inserir">
	</form>
	</td>

	<td>
	<form action="atualizar.php" method="get">
	<input type="hidden" name="area_id" value="{$area_id}">
	<input type="submit" value="Modificar">
	</form>
	</td>

	<td class="is-danger">
	<form action="excluir.php" method="get" onClick="return confirm('Tem certeza?');">
	<input type="hidden" name="area_id" value="{$area_id}">
	<input type="submit" value="Excluir">
	</form>
	</td>

	</tr>
	</tbody>
	</table>

	<table class="ficha">
	<tbody>
	<tr><th>Id</th><td>{$area.id}</td></tr>
	<tr><th>Área</th><td>{$area.area}</td></tr>
	<tr><th>Instituições</th><td>{$num_instituicoes}</td></tr>
	<tr>
	<th>Ações</th>
	<td>
	<span class="action-links">
	<a href="atualizar.php?area_id={$area.id}">Editar</a>
	<a class="is-danger" href="excluir.php?area_id={$area.id}" onclick="return confirm('Excluir esta área?');">Excluir</a>
	</span>
	</td>
	</tr>
	</tbody>
	</table>

	{if $instituicoes}

	<header class="page-head">
		<div class="page-heading">
			<h1>Instituições nesta Área</h1>
		</div>
	</header>

	<section class="table-shell is-scroll" aria-label="Instituições nesta área">
	<table id="instituicoes" class="display dt-table">
	<thead>
	<tr>
	<th>Instituição</th>
	<th>Turma</th>
	<th>Supervisores</th>
	<th>Endereço</th>
	<th>Telefone</th>
	</tr>
	</thead>
	<tbody>
	{section name=i loop=$instituicoes}
	<tr>
	<td class="inst-name"><a href="../instituicoes/ver_cada.php?instituicao_id={$instituicoes[i].id}">{$instituicoes[i].instituicao}</a></td>
	<td class="coluna_centralizada"><a href="../alunos/listar.php?seleciona_instituicao={$instituicoes[i].id}&seleciona_periodo={$instituicoes[i].turma}">{$instituicoes[i].turma}</a></td>
	<td class="coluna_centralizada"><a href="../supervisores/listar.php?instituicao_id={$instituicoes[i].id}">{$instituicoes[i].q_supervisores}</a></td>
	<td>{$instituicoes[i].endereco}</td>
	<td>{$instituicoes[i].telefone}</td>
	</tr>
	{/section}
	</tbody>
	</table>
	</section>

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
	{/if}

</main>

</body>

</html>
