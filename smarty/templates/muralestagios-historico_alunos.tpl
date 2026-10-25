<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Histórico alunos</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Mural</p>
			<h1>Histórico de alunos<span>Resumo da seleção de estágio {$periodo}</span></h1>
		</div>
	</header>

	<table class="ficha card-table">
	<tbody>
	<tr><th>Continuam sem estágio</th><td class="coluna_centralizada">{$sem_estagio}</td></tr>
	<tr><th>Não iniciaram estágio</th><td class="coluna_centralizada">{$niveis.0}</td></tr>
	<tr><th>Iniciaram estágio I</th><td class="coluna_centralizada">{$niveis.1}</td></tr>
	<tr><th>Iniciaram estágio II</th><td class="coluna_centralizada">{$niveis.2}</td></tr>
	<tr><th>Iniciaram estágio III</th><td class="coluna_centralizada">{$niveis.3}</td></tr>
	<tr><th>Iniciaram estágio IV</th><td class="coluna_centralizada">{$niveis.4}</td></tr>
	<tr class="rodape"><th>TOTAL</th><td class="coluna_centralizada">{$niveis.5}</td></tr>
	</tbody>
	</table>

	<section class="table-shell" aria-label="Alunos na seleção de estágio">
		<p class="eyebrow">Alunos na seleção de estágio · {$periodo}</p>
		<table id="alunos" class="display dt-table">
		<thead>
		<tr>
		<th>#</th>
		<th>Registro</th>
		<th>Nome</th>
		<th>Nível</th>
		</tr>
		</thead>
		<tbody>
		{assign var = 'j' value = 1}
		{section name=i loop=$alunos}
		<tr{if $alunos[i].situacao eq 0} class="sem-estagio"{/if}>
		<td class="coluna_direita">{$j++}</td>
		<td class="coluna_centralizada">{$alunos[i].id_aluno}</td>
		<td><a href="../alunos/ver_cada.php?registro={$alunos[i].id_aluno}" target="_blank">{$alunos[i].nome}</a></td>
		<td class="coluna_centralizada">{$alunos[i].nivel}</td>
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
	$('#alunos').DataTable({
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
