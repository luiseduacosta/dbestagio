<<<<<<< HEAD
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Histórico alunos</title>
<style type="text/css">
@import url("../estagio.css");
</style>
</head>
<body>

<p><a href='historico.php'>Voltar</a></p>

<div align='center'>

<table width='40%' border='1' summary="Resumo da seleção de estágio">
<caption>Resumo da seleção de estágio {$periodo}</caption>
<tbody>
	<tr><td>Continuam sem estágio</td><td style='text-align:center;'>{$sem_estagio}</td></tr>
	<tr><td>Não iniciaram estágio</td><td style='text-align:center;'>{$niveis.0}</td></tr>
	<tr><td>Iniciaram estágio I</td>  <td style='text-align:center;'>{$niveis.1}</td></tr>
	<tr><td>Iniciaram estágio II</td> <td style='text-align:center;'>{$niveis.2}</td></tr>
	<tr><td>Iniciaram estágio III</td><td style='text-align:center;'>{$niveis.3}</td></tr>
	<tr><td>Iniciaram estágio IV</td> <td style='text-align:center;'>{$niveis.4}</td></tr>
	<tr><th>TOTAL</th>                <th style='text-align:center;'>{$niveis.5}</th></tr>
</tbody>
</table>
	
<table border='1' summary="Alunos por periodo de seleção de estágio">
	<caption>Alunos seleção de estágio período {$periodo}</caption>
	<thead>
		<tr>
			<th>Id</th>
			<th><a href=?periodo={$periodo}&ordem=id_aluno>Registro</a></th>
			<th><a href=?periodo={$periodo}&ordem=nome>Nome</a></th>
			<th>Nivel</th>
		</tr>
	</thead>
	<tbody>
		{assign var = 'j' value = 1}
		{section name=i loop=$alunos}
			{if $alunos[i].situacao eq 0}
				<tr style='background-color:#e7e1ae'>
			{elseif $alunos[i].situacao eq 1}
				<tr>
			{/if}
			<td style='text-align:right;'>{$j++}</td>
			<td style='text-align:center;'>{$alunos[i].id_aluno}</td>
			<td><a href='../alunos/ver_cada.php?registro={$alunos[i].id_aluno}' target='_blank'>{$alunos[i].nome}</a></td>
			<td style='text-align:center;'>{$alunos[i].nivel}</td>
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
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa
