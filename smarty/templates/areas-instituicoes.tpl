<<<<<<< HEAD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Instituições por Área</title>
</head>
<body>

<a href="javascript:history.back();">Voltar</a><br>

<div align="center">
<h3>Instituições da área: {$nome_area}</h3>
</div>

<table id="instituicoes" class="display">
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

{section name=elemento loop=$instituicoes}
<tr>
<td><a href="../../instituicoes/ver_cada.php?instituicao_id={$instituicoes[elemento].id}">{$instituicoes[elemento].instituicao}</a></td>
<td><a href="../../alunos/listar.php?seleciona_instituicao={$instituicoes[elemento].id}&seleciona_periodo={$instituicoes[elemento].turma}">{$instituicoes[elemento].turma}</a></td>
<td class="coluna_centralizada"><a href="../supervisores/listar.php?instituicao_id={$instituicoes[elemento].id}">{$instituicoes[elemento].q_supervisores}</a></td>
<td>{$instituicoes[elemento].endereco}</td>
<td>{$instituicoes[elemento].telefone}</td>
</tr>
{/section}

</tbody>
</table>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#instituicoes').DataTable({
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
=======
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Instituições por Área</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · ESS/UFRJ</p>
			<h1>Instituições da área<span>{$nome_area}</span></h1>
		</div>
	</header>

	<section class="table-shell is-scroll" aria-label="Instituições da área">

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

		{section name=elemento loop=$instituicoes}
		<tr>
		<td class="inst-name"><a href="../instituicoes/ver_cada.php?instituicao_id={$instituicoes[elemento].id}">{$instituicoes[elemento].instituicao}</a></td>
		<td class="coluna_centralizada"><a href="../alunos/listar.php?seleciona_instituicao={$instituicoes[elemento].id}&seleciona_periodo={$instituicoes[elemento].turma}">{$instituicoes[elemento].turma}</a></td>
		<td class="coluna_centralizada"><a href="../supervisores/listar.php?instituicao_id={$instituicoes[elemento].id}">{$instituicoes[elemento].q_supervisores}</a></td>
		<td>{$instituicoes[elemento].endereco}</td>
		<td>{$instituicoes[elemento].telefone}</td>
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
