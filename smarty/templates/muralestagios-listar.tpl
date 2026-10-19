<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="light">
	<title>Mural de Estágios — Estágio ESS/UFRJ</title>
	<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
	<link rel="stylesheet" href="../estagio.css">
	<link rel="stylesheet" href="../estagio-moderno.css">
</head>

<body id="corpo">

	<form id="confirmaInscricao" hidden>
		<input type="hidden" name="registroInserido" id="registroInserido" value="{$insere|escape:'html'}">
	</form>

	<span id="alunoInscrito" class="flash-message" role="status" aria-live="polite" hidden></span>

	<main class="mural-page">
		<header class="page-head">
			<div class="page-heading">
				<p class="eyebrow">Coordenação de Estágio e Extensão · ESS/UFRJ</p>
				<h1>Mural de estágios<span>{if $periodo == ''}Todos os períodos{else}Turma {$periodo}{/if}</span></h1>
			</div>

			{if $is_admin}
			<form class="periodo-form" name="seleciona_periodo" method="get" action="mural-listar.php">
				<label for="periodo">Período exibido</label>
				<select name="periodo" id="periodo" onChange="this.form.submit();">
					<option value=""{if $periodo == ''} selected{/if}>Todos os períodos</option>
					{section name=periodo_item loop=$periodos}
					<option value="{$periodos[periodo_item]}"{if $periodos[periodo_item] == $periodo} selected{/if}>{$periodos[periodo_item]}</option>
					{/section}
				</select>
				<noscript><button type="submit">Ver</button></noscript>
			</form>
			{/if}
		</header>

		<div class="summary-strip" aria-label="Resumo do mural">
			<span class="metric"><strong>{$totalVagas}</strong> vagas</span>
			<span class="metric"><strong>{$totalAlunos}</strong> alunos</span>
			<span class="metric"><strong>{$alunos_novos}</strong> novos</span>
			<span class="metric"><strong>{$alunosVelhos}</strong> estagiários</span>
		</div>

		<section class="table-shell" aria-label="Ofertas de estágio do mural">
			<table id="mural" class="display">
				<caption class="sr-only">Ofertas de estágio por instituição, vagas, inscritos, benefícios e prazos de encerramento e seleção.</caption>
				<thead>
					<tr>
						<th scope="col">Instituição</th>
						<th scope="col" class="coluna_centralizada">Vagas</th>
						<th scope="col" class="coluna_centralizada">Inscritos</th>
						<th scope="col">Benefícios</th>
						<th scope="col" class="coluna_centralizada">Encerramento</th>
						<th scope="col" class="coluna_centralizada">Seleção</th>
					</tr>
				</thead>

				<tbody>
					{section name=item loop=$instituicao}
					<tr>
						<td>
							<a class="inst-name" href="mural-ver_cada.php?muralestagio_id={$instituicao[item].muralestagio_id}">{$instituicao[item].instituicao}</a>
							<span class="convenio-badge {if $instituicao[item].convenio == 0}nao{else}sim{/if}">{if $instituicao[item].convenio == 0}Não conveniada{else}Conveniada{/if}</span>
						</td>
						<td class="coluna_centralizada">{$instituicao[item].vagas}</td>
						<td class="coluna_centralizada">
							<a href="inscritos.php?muralestagio_id={$instituicao[item].muralestagio_id}">{$instituicao[item].quantidade_alunos}</a>
						</td>
						<td>{$instituicao[item].beneficios}</td>
						<td class="coluna_centralizada" data-order="{$instituicao[item].data_inscricao_iso}">
							{if $instituicao[item].data_inscricao == ''}Sem data{else}{$instituicao[item].data_inscricao}{/if}
						</td>
						<td class="coluna_centralizada" data-order="{$instituicao[item].data_selecao_iso}">
							{if $instituicao[item].data_selecao == ''}Sem data{else}{$instituicao[item].data_selecao} Horário: {$instituicao[item].horario_selecao}{/if}
						</td>
					</tr>
					{sectionelse}
					<tr>
						<td colspan="6" class="empty-state">Nenhuma oferta de estágio encontrada para este período.</td>
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
			var input = document.getElementById('registroInserido');
			var message = document.getElementById('alunoInscrito');

			if (input && message && input.value.trim() !== '') {
				message.textContent = 'Aluno(a) ' + input.value.trim() + ' inscrito(a) em seleção de estágio.';
				message.hidden = false;
			}

			$('#mural').DataTable({
				language: { url: '../libjs/datatables/pt-BR.json' },
				pageLength: 25,
				lengthMenu: [10, 25, 50, 100],
				order: []
			});
		});
	</script>
</body>
</html>
