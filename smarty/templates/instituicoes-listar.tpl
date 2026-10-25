<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Lista de instituições</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

{literal}
<script type="text/javascript">
function carrega_tabela() {
	turma = document.getElementById('turma').value;
	natureza = document.getElementById('natureza').value;
	instituicao = document.getElementById('instituicao').value;
	window.location = "listar.php?turma=" + encodeURIComponent(turma)
		+ "&instituicao=" + encodeURIComponent(instituicao)
		+ "&natureza=" + encodeURIComponent(natureza);
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
			<h1>Instituições<span>Instituições conveniadas, alunos e supervisores por período</span></h1>
		</div>

		<form class="periodo-form">
			<input type="hidden" name="instituicao" id="instituicao" value="{$instituicao}">

			<label for="turma">Período</label>
			<select name="turma" id="turma" onChange="return carrega_tabela();">
			<option value="{$turma}">Período: {if $turma}{$turma}{else}Todos{/if}</option>
			<option value="0">Todos</option>
			{section name=i loop=$periodos}
			<option value="{$periodos[i]}">{$periodos[i]}</option>
			{/section}
			</select>

			<label for="natureza">Natureza</label>
			<select name="natureza" id="natureza" onChange="return carrega_tabela();">
			<option value="{$natureza}">Natureza: {$natureza}</option>
			<option value="0">Todos</option>
			{section name=i loop=$naturezas}
			<option value="{$naturezas[i]}">{$naturezas[i]}</option>
			{/section}
			</select>
		</form>
	</header>

	<div class="summary-strip" aria-label="Resumo das instituições">
		<span class="metric"><strong><a href="../professores/listar.php?periodo={$turma}">{$total_professores}</a></strong> professores</span>
		<span class="metric"><strong>{$total_instituicoes}</strong> instituições</span>
		<span class="metric"><strong>{$total_supervisores}</strong> supervisores</span>
		<span class="metric"><strong>{$total_alunos}</strong> alunos</span>
		<span class="metric"><strong>{$total_periodos}</strong> períodos</span>
	</div>

	<section class="table-shell is-scroll" aria-label="Instituições">

		<table id="instituicoes" class="display dt-table">
		<caption>Tabela de instituições {if $turma}{$turma}{else}(todos os períodos){/if}</caption>

		<thead>
		<tr>
		<th>Id</th>
		<th>Convênio</th>
		<th>Instituições</th>
		<th>Seguro</th>
		<th>Benefícios</th>
		<th>Turma</th>
		<th>Alunos</th>
		<th>Períodos</th>
		<th>Supervisores</th>
		<th>Áreas</th>
		<th>Natureza</th>
		</tr>
		</thead>

		<tbody>

		{assign var="i" value=1}
		{section name=elementos loop=$instituicoes}
		<tr>
		<td class="coluna_direita">{$i++}</td>

		{* Convenio *}
		{if $instituicoes[elementos].convenio != 0}
			<td class="coluna_direita"><a href="http://www.pr1.ufrj.br/estagios/info.php?codEmpresa={$instituicoes[elementos].convenio}">{$instituicoes[elementos].convenio}</a></td>
		{else}
			<td class="coluna_direita">&nbsp;</td>
		{/if}

		{* Instituicoes *}
		<td class="inst-name"><a href="ver_cada.php?instituicao_id={$instituicoes[elementos].instituicao_id}">{$instituicoes[elementos].instituicao}</a></td>

		{* Seguro *}
		{if $instituicoes[elementos].seguro eq 0}
			<td>Instituição</td>
		{else}
			<td>UFRJ</td>
		{/if}

		{* Beneficios *}
		<td>{$instituicoes[elementos].beneficio}</td>

		{* Turma *}
		{if $turma}
			<td class="coluna_centralizada"><a href="../alunos/listar.php?seleciona_instituicao={$instituicoes[elementos].instituicao_id}&seleciona_periodo={$turma}">{$turma}</a></td>
		{else}
			<td class="coluna_centralizada"><a href="../alunos/listar.php?seleciona_instituicao={$instituicoes[elementos].instituicao_id}&seleciona_periodo={$instituicoes[elementos].turma}">{$instituicoes[elementos].turma}</a></td>
		{/if}

		{* Alunos *}
		{if $turma}
			<td class="coluna_centralizada"><a href="../alunos/listar.php?seleciona_instituicao={$instituicoes[elementos].instituicao_id}&seleciona_periodo={$turma}">{$instituicoes[elementos].alunos}</a></td>
		{else}
			{if $instituicoes[elementos].alunos == 0}
				<td class="coluna_centralizada">{$instituicoes[elementos].alunos}</td>
			{else}
				<td class="coluna_centralizada"><a href="../alunos/listar.php?seleciona_instituicao={$instituicoes[elementos].instituicao_id}&seleciona_periodo={$instituicoes[elementos].turma}">{$instituicoes[elementos].alunos}</a></td>
			{/if}
		{/if}

		{* Periodos *}
		<td class="coluna_centralizada">{$instituicoes[elementos].periodos}</td>

		{* Supervisores *}
		{if $instituicoes[elementos].supervisores == 0}
			<td class="coluna_centralizada">{$instituicoes[elementos].supervisores}</td>
		{else}
			<td class="coluna_centralizada">
			<a href="../supervisores/listar.php?instituicao_id={$instituicoes[elementos].instituicao_id}">{$instituicoes[elementos].supervisores}</a>
			</td>
		{/if}

		{* Area *}
		<td>{$instituicoes[elementos].area}</td>

		{* Natureza *}
		<td>{$instituicoes[elementos].natureza}</td>

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
