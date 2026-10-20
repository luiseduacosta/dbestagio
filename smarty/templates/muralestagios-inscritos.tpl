<!doctype html>
<html lang="pt-BR">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="light">
	<title>Alunos inscritos no mural — Estágio ESS/UFRJ</title>
	<meta name="description" content="Alunos inscritos nas ofertas do mural de estágios">
	<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
	<link rel="stylesheet" href="../estagio.css">
	<link rel="stylesheet" href="../estagio-moderno.css">
</head>

<body id="corpo">

	<main class="mural-page">
		<header class="page-head">
			<div class="page-heading">
				<p class="eyebrow">Coordenação de Estágio e Extensão · ESS/UFRJ</p>
				<h1>Alunos inscritos no mural<span>{if $instituicao != ''}Oferta: {$instituicao}{if $periodo != ''} · Turma {$periodo}{/if}{elseif $periodo != ''}Turma {$periodo}{else}Todos os períodos{/if}</span></h1>
			</div>

			<form class="periodo-form" name="filtro_inscritos" method="get" action="inscritos.php">

				{* O seletor de período é exclusivo do admin; os demais perfis sempre veem
				   o período atual e o parâmetro nem é enviado. *}
				{if $is_admin}
				<label for="periodo">Período</label>
				<select name="periodo" id="periodo" onChange="this.form.submit();">
					<option value=""{if $periodo == ''} selected{/if}>Todos os períodos</option>
					{section name=p loop=$periodos}
					<option value="{$periodos[p]}"{if $periodos[p] == $periodo} selected{/if}>{$periodos[p]}</option>
					{/section}
				</select>
				{/if}

				<label for="muralestagio_id">Oferta</label>
				<select name="muralestagio_id" id="muralestagio_id" onChange="this.form.submit();">
					<option value="0"{if $muralestagio_id == 0} selected{/if}>Todas as ofertas</option>
					{section name=o loop=$ofertas}
					<option value="{$ofertas[o].id}"{if $ofertas[o].id == $muralestagio_id} selected{/if}>{$ofertas[o].instituicao}{if $periodo == ''} ({$ofertas[o].periodo}){/if}</option>
					{/section}
				</select>

				<label for="grupo">Grupo</label>
				<select name="grupo" id="grupo" onChange="this.form.submit();">
					<option value="todos"{if $grupo == 'todos'} selected{/if}>Todos os alunos</option>
					<option value="novos"{if $grupo == 'novos'} selected{/if}>Alunos novos</option>
					<option value="conhecidos"{if $grupo == 'conhecidos'} selected{/if}>Alunos que já estagiaram</option>
				</select>

				<noscript><button type="submit">Filtrar</button></noscript>
			</form>
		</header>

		<div class="summary-strip" aria-label="Resumo dos inscritos">
			<span class="metric"><strong>{$totalAlunos}</strong> {if $totalAlunos == 1}aluno listado{else}alunos listados{/if}</span>
		</div>

		<div class="action-row">
			<a class="btn-secondary" href="mural-listar.php">Voltar para o mural</a>

			{* A impressão em PDF só existe para uma oferta do período atual. *}
			{if $muralestagio_id > 0 && $periodo == $periodo_atual}
			<a class="btn" href="imprime_inscricoes.php?muralestagio_id={$muralestagio_id}">Imprimir inscrições</a>
			{/if}
		</div>

		<section class="table-shell is-scroll" aria-label="Alunos inscritos nas ofertas do mural">
			<table id="inscritos" class="display dt-table">
				<caption class="sr-only">Nome, dados acadêmicos, situação e contato dos alunos inscritos nas ofertas do mural de estágios.</caption>
				<thead>
					<tr>
						<th scope="col" class="coluna_direita">#</th>
						<th scope="col" class="coluna_centralizada">TC</th>
						<th scope="col" class="coluna_direita">DRE</th>
						<th scope="col">Nome</th>
						<th scope="col" class="coluna_centralizada">Nível</th>
						<th scope="col" class="coluna_centralizada">Ingresso</th>
						<th scope="col" class="coluna_centralizada">Turno</th>
						<th scope="col" class="coluna_centralizada">Período(s)</th>
						<th scope="col">Ofertas</th>
						<th scope="col" class="coluna_centralizada">Situação</th>
						<th scope="col">Último estágio</th>
						<th scope="col" class="coluna_centralizada">Início</th>
						<th scope="col" class="coluna_centralizada">Última data</th>
						<th scope="col" class="coluna_centralizada">Qtd</th>
						{if $sistema_autentica == 1}
						<th scope="col">E-mail</th>
						<th scope="col" class="coluna_centralizada">Telefone</th>
						<th scope="col" class="coluna_centralizada">Celular</th>
						{if $is_admin}
						<th scope="col" data-orderable="false">Excluir</th>
						{/if}
						{/if}
					</tr>
				</thead>

				<tbody>
					{* Uma linha por aluno; a situação (novo x já estagiou) é mostrada
					   como etiqueta na coluna "Situação". *}
					{section name=i loop=$alunos}
					<tr>
						<td class="coluna_direita">{$smarty.section.i.index+1}</td>
						<td class="coluna_centralizada">{$alunos[i].tc}</td>
						<td class="coluna_direita">{$alunos[i].registro}</td>
						<td>
							{if $sistema_autentica == 1}
							<a href="../alunos/ver_cada.php?registro={$alunos[i].registro}">{$alunos[i].nome}</a>
							{else}
							{$alunos[i].nome}
							{/if}
						</td>
						<td class="coluna_centralizada">{$alunos[i].nivel}</td>
						<td class="coluna_centralizada">{$alunos[i].data_ingresso}</td>
						<td class="coluna_centralizada">{$alunos[i].turno}</td>
						<td class="coluna_centralizada">{$alunos[i].periodos_texto}</td>
						<td>{$alunos[i].ofertas_texto}</td>
						<td class="coluna_centralizada">
							<span class="situacao-badge {if $alunos[i].situacao == 'novo'}novo{else}estagiario{/if}">{$alunos[i].situacao_texto}</span>
						</td>
						<td data-order="{$alunos[i].estagio_periodo}">{$alunos[i].estagio_instituicao}{if $alunos[i].estagio_periodo != ''} ({$alunos[i].estagio_periodo}){/if}</td>
						<td class="coluna_centralizada" data-order="{$alunos[i].data_inicio}">{$alunos[i].data_inicio_txt}</td>
						<td class="coluna_centralizada" data-order="{$alunos[i].data_ultima}">{$alunos[i].data_ultima_txt}</td>
						<td class="coluna_centralizada">{$alunos[i].quantidade}</td>

						{* Dados de contato e ações só para usuário autenticado. *}
						{if $sistema_autentica == 1}
						<td>{$alunos[i].email}</td>
						<td class="coluna_centralizada">{$alunos[i].telefone}</td>
						<td class="coluna_centralizada">{$alunos[i].celular}</td>

						{if $is_admin}
						<td class="action-links">
							{if $muralestagio_id > 0}
							{* Filtrado por oferta: exclui só a inscrição naquela oferta. *}
							<form class="form-excluir" method="post"
								action="../inscricoes/excluir.php?muralestagio_id={$muralestagio_id}&periodo={$periodo}"
								onSubmit="return confirm('Excluir esta inscrição?');">
								<input type="hidden" name="id" value="{$alunos[i].inscricao_id}">
								<input type="submit" name="submit" value="Excluir">
							</form>
							{else}
							{* Sem filtro de oferta: tira o aluno do processo no período atual. *}
							<a class="is-danger" href="mural-excluir_aluno.php?aluno_id={$alunos[i].aluno_id}&registro={$alunos[i].registro}"
								onclick="return confirm('Confirma a remoção das inscrições deste aluno no período atual?');">Excluir</a>
							{/if}
						</td>
						{/if}
						{/if}
					</tr>
					{sectionelse}
					<tr>
						<td colspan="{if $sistema_autentica == 1}{if $is_admin}18{else}17{/if}{else}14{/if}" class="empty-state">Nenhum aluno inscrito encontrado para este filtro.</td>
					</tr>
					{/section}
				</tbody>
			</table>
		</section>
	</main>

	<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
	<script src="../libjs/datatables/dataTables.min.js"></script>
	<script>
		$(function () {
			$('#inscritos').DataTable({
				language: { url: '../libjs/datatables/pt-BR.json' },
				pageLength: 25,
				lengthMenu: [10, 25, 50, 100],
				order: []
			});
		});
	</script>
</body>

</html>
