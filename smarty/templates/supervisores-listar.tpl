<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tabela de Supervisores</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

{literal}
<script type="text/javascript">
function carrega_tabela() {
	var turma = document.getElementById('turma').value;
	window.location = "listar.php?turma=" + encodeURIComponent(turma);
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
			<h1>Supervisores<span>Supervisores de campo vinculados às instituições</span></h1>
		</div>

		{if empty($instituicao_id)}
		<form class="periodo-form">
			<label for="turma">Período</label>
			<select name="turma" id="turma" onChange="return carrega_tabela();">
			<option value="0">Selecione período (Todos)</option>
			{foreach from=$periodos item=p}
			<option value="{$p}" {if $p == $turma}selected{/if}>{$p}</option>
			{/foreach}
			</select>
		</form>
		{/if}
	</header>

	<section class="table-shell" aria-label="Supervisores">
	<table id="supervisores" class="display dt-table">
	<thead>
	<tr>
	<th>#</th>
	<th>Cress</th>
	<th>Supervisor</th>
	<th>Períodos</th>
	{if $sistema_autentica == 1}
		<th>E-mail</th>
		<th>Celular</th>
		<th>Telefone</th>
	{/if}
	<th>Instituição</th>
	<th>Turma</th>
	<th>Curso</th>
	<th data-orderable="false">Ações</th>
	</tr>
	</thead>
	<tbody>

	{foreach from=$supervisores item=s key=idx}
	<tr>
	<td class="coluna_direita">{$idx+1}</td>
	<td class="coluna_direita">{$s.cress}</td>
	<td>
		<a href="ver_cada.php?supervisor_id={$s.supervisor_id}">{$s.nome}</a>
	</td>
	<td class="coluna_direita">{$s.q_periodos}</td>

	{if $sistema_autentica == 1}
		<td>{if $s.email}<a href="mailto:{$s.email}">{$s.email}</a>{else}—{/if}</td>
		<td>{$s.celular}</td>
		<td>{$s.telefone}</td>
	{/if}

	<td>
	{if $s.instituicao_id}
		<a href="../instituicoes/ver_cada.php?instituicao_id={$s.instituicao_id}">{$s.instituicao}</a>
	{else}
		{$s.instituicao}
	{/if}
	</td>

	<td class="coluna_direita">
	{if $s.turma}
		<a href="alunos_supervisor.php?supervisor_id={$s.supervisor_id}&nome_supervisor={$s.nome|escape:'url'}">{$s.turma}</a>
	{else}
		—
	{/if}
	</td>

	<td class="coluna_direita">
	{if $s.id_curso}
		<a href="../curso/ver_cada_supervisor.php?id_supervisor={$s.id_curso}">{$s.id_curso}</a>
	{else}
		—
	{/if}
	</td>

	<td>
		<span class="action-links">
		<a href="ver_cada.php?supervisor_id={$s.supervisor_id}">Ver</a>
		<a href="atualizar.php?supervisor_id={$s.supervisor_id}">Editar</a>
		<a class="is-danger" href="excluir.php?supervisor_id={$s.supervisor_id}" onclick="return confirm('Excluir este supervisor?');">Excluir</a>
		</span>
	</td>
	</tr>
	{/foreach}

	</tbody>
	</table>
	</section>

	<div class="action-row">
		<a class="action-btn" href="inserir.php">Inserir novo supervisor</a>
	</div>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#supervisores').DataTable({
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
