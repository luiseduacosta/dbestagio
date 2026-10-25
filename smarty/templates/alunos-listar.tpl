<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Alunos</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

{literal}
<script type="text/javascript">
function carrega_tabela() {
	periodo = document.getElementById('periodo').value;
	instituicao_id = document.getElementById('instituicao_id').value;
	window.location = "listar.php?periodo=" + periodo + "&instituicao_id=" + instituicao_id;
	return false;
}
</script>
{/literal}

</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Alunos</p>
			<h1>Lista de alunos<span>Registros de alunos e seus períodos de estágio</span></h1>
		</div>

		<form class="periodo-form" method="get" action="listar.php" onsubmit="return false;">
			<div>
				<label for="busca">Buscar (nome ou registro)</label>
				<input type="text" id="busca" name="busca" value="{$busca}" onkeyup="if(event.key=='Enter')window.location='listar.php?busca='+this.value+'&periodo={$periodo}&instituicao_id={$instituicao_id}';">
			</div>
			<div>
				<label for="periodo">Período</label>
				<select name="periodo" id="periodo" onChange="return carrega_tabela();">
					<option value="">Todos os períodos</option>
					{section name=i loop=$periodos}
					<option value="{$periodos[i]}" {if $periodos[i] == $periodo}selected{/if}>{$periodos[i]}</option>
					{/section}
				</select>
			</div>
			<div>
				<label for="instituicao_id">Instituição de estágio</label>
				<select name="instituicao_id" id="instituicao_id" onChange="return carrega_tabela();">
					<option value="0">Todas as instituições</option>
					{section name=i loop=$instituicoes}
					<option value="{$instituicoes[i].id}" {if $instituicoes[i].id == $instituicao_id}selected{/if}>{$instituicoes[i].instituicao}</option>
					{/section}
				</select>
			</div>
		</form>
	</header>

	<section class="table-shell" aria-label="Lista de alunos">
		<table id="alunos" class="display dt-table">
			<thead>
			<tr>
			<th>Registro</th>
			<th>Nome</th>
			<th>Ingresso</th>
			<th>Períodos de estágio</th>
			<th>Nº estágios</th>
			{if $smarty.cookies.usuario}
			<th>Celular</th>
			<th>E-mail</th>
			<th data-orderable="false">Ações</th>
			{/if}
			</tr>
			</thead>
			<tbody>

			{section name=i loop=$alunos}
			<tr>
			<td class="coluna_direita">{$alunos[i].registro}</td>
			<td>
			{if $smarty.cookies.usuario}
			<a href="ver_cada.php?aluno_id={$alunos[i].id}">{$alunos[i].nome}</a>
			{else}
			{$alunos[i].nome}
			{/if}
			</td>
			<td class="coluna_centralizada">{$alunos[i].ingresso}</td>
			<td>{$alunos[i].periodos_estagio}</td>
			<td class="coluna_centralizada">{$alunos[i].num_estagios}</td>
			{if $smarty.cookies.usuario}
			<td>{$alunos[i].celular}</td>
			<td>{$alunos[i].email}</td>
			<td>
			<span class="action-links">
			<a href="ver_cada.php?aluno_id={$alunos[i].id}">Ver</a>
			{if $isAdmin}
			<a href="atualizar.php?aluno_id={$alunos[i].id}">Editar</a>
			<a class="is-danger" href="excluir.php?aluno_id={$alunos[i].id}" onclick="return confirm('Excluir este aluno?');">Excluir</a>
			{/if}
			</span>
			</td>
			{/if}
			</tr>
			{/section}

			</tbody>
		</table>
	</section>

	<div class="action-row">
		{if $isAdmin}
		<a class="action-btn" href="inserir.php">Novo aluno</a>
		{/if}
		<a class="btn btn-secondary" href="javascript:history.back();">Voltar</a>
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
