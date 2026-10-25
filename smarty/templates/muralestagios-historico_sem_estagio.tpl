<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Histórico alunos sem estágio</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Mural</p>
			<h1>Alunos sem estágio<span>Período {$periodo}</span></h1>
		</div>
	</header>

	<section class="table-shell" aria-label="Alunos sem estágio">
	<table id="sem_estagio" class="display dt-table">
	<thead>
	<tr>
	<th>#</th>
	<th>Registro</th>
	<th>Nome</th>
	<th>E-mail</th>
	<th>Telefone</th>
	<th>Celular</th>
	</tr>
	</thead>
	<tbody>
	{assign var = 'j' value = 1}
	{section name=i loop=$sem_estagio}
	<tr>
	<td class="coluna_direita">{$j++}</td>
	<td class="coluna_centralizada">{$sem_estagio[i].id_aluno}</td>
	<td><a href="../alunos/ver_cada.php?registro={$sem_estagio[i].id_aluno}" target="_blank">{$sem_estagio[i].nome}</a></td>
	<td>{if $sem_estagio[i].email}<a href="mailto:{$sem_estagio[i].email}">{$sem_estagio[i].email}</a>{else}—{/if}</td>
	<td>{$sem_estagio[i].telefone}</td>
	<td>{$sem_estagio[i].celular}</td>
	</tr>
	{/section}
	</tbody>
	</table>
	</section>

	<div class="action-row">
		<a class="btn btn-secondary" href="historico.php">Voltar</a>
	</div>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#sem_estagio').DataTable({
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
