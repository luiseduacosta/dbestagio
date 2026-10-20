<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Estagiários — Estágio ESS/UFRJ</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

{literal}
<script type="text/javascript">
function carrega_tabela() {
	var form = document.forms['filtros'];
	var campos = ['periodo', 'instituicao_id', 'supervisor_id', 'professor_id', 'nivel', 'busca'];
	var params = [];
	for (var i = 0; i < campos.length; i++) {
		var el = form.elements[campos[i]];
		if (el && el.value !== '') {
			params.push(campos[i] + '=' + encodeURIComponent(el.value));
		}
	}
	window.location = 'listar.php' + (params.length ? '?' + params.join('&') : '');
	return false;
}
</script>
{/literal}

</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · ESS/UFRJ</p>
			<h1>Estagiários<span>Estágios registrados por período</span></h1>
		</div>

		<form class="periodo-form" name="filtros" onsubmit="return carrega_tabela();">
			<label for="periodo">Período</label>
			<select id="periodo" name="periodo" onChange="return carrega_tabela();">
				<option value="">Todos os períodos</option>
				{foreach item=p from=$periodos}
				<option value="{$p}" {if $p == $periodo}selected{/if}>{$p}</option>
				{/foreach}
			</select>

			<label for="instituicao_id">Instituição</label>
			<select id="instituicao_id" name="instituicao_id" onChange="return carrega_tabela();">
				<option value="">Todas as instituições</option>
				{foreach item=i from=$instituicoes}
				<option value="{$i.id}" {if $i.id == $instituicao_id}selected{/if}>{$i.instituicao}</option>
				{/foreach}
			</select>

			<label for="supervisor_id">Supervisor</label>
			<select id="supervisor_id" name="supervisor_id" onChange="return carrega_tabela();">
				<option value="">Todos os supervisores</option>
				{foreach item=s from=$supervisores}
				<option value="{$s.id}" {if $s.id == $supervisor_id}selected{/if}>{$s.nome}</option>
				{/foreach}
			</select>

			<label for="professor_id">Professor</label>
			<select id="professor_id" name="professor_id" onChange="return carrega_tabela();">
				<option value="">Todos os professores</option>
				{foreach item=p from=$professores}
				<option value="{$p.id}" {if $p.id == $professor_id}selected{/if}>{$p.nome}</option>
				{/foreach}
			</select>

			<label for="nivel">Nível</label>
			<select id="nivel" name="nivel" onChange="return carrega_tabela();">
				{foreach item=n from=$niveis}
				<option value="{$n}" {if $n == $nivel}selected{/if}>{if $n == ''}Todos os níveis{else}Nível {$n}{/if}</option>
				{/foreach}
			</select>

			<label for="busca">Busca</label>
			<input type="text" id="busca" name="busca" value="{$busca}" onkeyup="if (event.keyCode == 13) return carrega_tabela();">
			<button type="submit" class="btn btn-sm">Filtrar</button>
		</form>
	</header>

	<section class="table-shell" aria-label="Estagiários">
		<table id="estagiarios" class="display dt-table">
		<thead>
		<tr>
		<th>Período</th>
		<th>Registro</th>
		<th>Aluno</th>
		<th>Instituição</th>
		<th>Supervisor</th>
		<th>Professor</th>
		<th>Nível</th>
		<th>Nota</th>
		<th data-orderable="false">Ações</th>
		</tr>
		</thead>
		<tbody>

		{foreach item=e from=$estagiarios}
		<tr>
		<td class="coluna_centralizada">{$e.periodo}</td>
		<td class="coluna_centralizada">{$e.registro}</td>
		<td>
		<a href="ver_cada.php?id={$e.id}">{$e.aluno_nome}</a>
		</td>
		<td>{$e.instituicao}</td>
		<td>{$e.supervisor}</td>
		<td>{$e.professor}</td>
		<td class="coluna_centralizada">{$e.nivel}</td>
		<td class="coluna_centralizada">{$e.nota}</td>
		<td>
		<span class="action-links">
		<a href="ver_cada.php?id={$e.id}">Ver</a>
		{if $isAdmin}
		<a href="atualizar.php?id={$e.id}">Editar</a>
		<a class="is-danger" href="excluir.php?id={$e.id}" onclick="return confirm('Excluir este estágio?');">Excluir</a>
		{/if}
		</span>
		</td>
		</tr>
		{/foreach}

		</tbody>
		</table>
	</section>

	<div class="action-row">
		{if $isAdmin}
		<a class="action-btn" href="inserir.php?periodo={$periodo}">Novo estágio</a>
		{/if}
		<a class="btn btn-secondary" href="javascript:history.back();">Voltar</a>
	</div>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#estagiarios').DataTable({
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
