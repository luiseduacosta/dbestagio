<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ver turno</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Turnos</p>
			<h1>Turno: {$t.turno}<span>{$num_alunos} aluno(s) vinculado(s)</span></h1>
		</div>
	</header>

	<table class="ficha card-table">
	<tbody>
	<tr><th>Id</th><td>{$t.id}</td></tr>
	<tr><th>Turno</th><td>{$t.turno}</td></tr>
	<tr><th>Alunos</th><td>{$num_alunos}</td></tr>
	</tbody>
	</table>

	{if $alunos}
	<section class="table-shell" aria-label="Alunos neste turno">
		<p class="eyebrow">Alunos neste turno</p>
		<table id="alunos" class="display dt-table">
		<thead>
		<tr>
		<th>Registro</th>
		<th>Nome</th>
		<th>Ingresso</th>
		</tr>
		</thead>
		<tbody>
		{foreach from=$alunos item=al}
		<tr>
		<td class="coluna_direita">{$al.registro}</td>
		<td><a href="../alunos/ver_cada.php?aluno_id={$al.id}">{$al.nome}</a></td>
		<td class="coluna_centralizada">{$al.ingresso}</td>
		</tr>
		{/foreach}
		</tbody>
		</table>
	</section>
	{/if}

	<div class="action-row">
		<a class="action-btn" href="atualizar.php?id={$t.id}">Editar turno</a>
		<a class="btn btn-danger" href="excluir.php?id={$t.id}" onclick="return confirm('Tem certeza que deseja excluir este turno?');">Excluir</a>
		<a class="btn btn-secondary" href="listar.php">Voltar para a listagem</a>
	</div>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	// A tabela so existe quando o turno tem alunos vinculados.
	if ($('#alunos').length) {
		$('#alunos').DataTable({
			language: { url: '../libjs/datatables/pt-BR.json' },
			pageLength: 25,
			lengthMenu: [10, 25, 50, 100],
			order: []
		});
	}
});
</script>
{/literal}

</body>
</html>
