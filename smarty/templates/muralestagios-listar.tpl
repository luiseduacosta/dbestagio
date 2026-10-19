<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="light">
	<title>Mural de Estágios — Estágio ESS/UFRJ</title>
	<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
	<link rel="stylesheet" href="../estagio.css">
	<style>
		:root {
			--mural-accent: #0B5CAB;
			--mural-accent-deep: #084A8A;
			--mural-soft: #E8F0FA;
			--mural-canvas: #F4F7F9;
			--mural-surface: #FFFFFF;
			--mural-ink: #17222B;
			--mural-muted: #52616D;
			--mural-line: #D7E0E7;
			--mural-line-strong: #B8C5CF;
			--mural-success: #176B3A;
			--mural-success-soft: #EAF5EE;
			--mural-warning: #8A4B00;
			--mural-warning-soft: #FFF4E5;
			--mural-focus: #A15C00;
		}

		body#corpo {
			width: 100%;
			max-width: none;
			min-height: 100%;
			margin: 0;
			padding: 24px clamp(18px, 3vw, 36px) 40px;
			background: var(--mural-canvas);
			color: var(--mural-ink);
			font-family: "Source Sans 3", "Noto Sans", "Segoe UI", Arial, sans-serif;
			font-size: 16px;
			line-height: 1.5;
			box-sizing: border-box;
		}

		body#corpo *, .mural-page * { box-sizing: border-box; }
		body#corpo [hidden] { display: none !important; }

		.mural-page {
			width: 100%;
			max-width: none;
			margin: 0 auto;
		}

		.page-head {
			display: flex;
			flex-wrap: wrap;
			align-items: flex-end;
			justify-content: space-between;
			gap: 16px 24px;
			margin-bottom: 16px;
			padding-bottom: 16px;
			border-bottom: 1px solid var(--mural-line);
		}

		.eyebrow {
			margin: 0 0 8px;
			color: var(--mural-accent);
			font-family: "IBM Plex Mono", Consolas, "Liberation Mono", monospace;
			font-size: 10px;
			font-weight: 500;
			letter-spacing: 0.12em;
			line-height: 1.4;
			text-transform: uppercase;
		}

		body#corpo h1 {
			margin: 0;
			padding: 0;
			background: none;
			color: var(--mural-ink);
			font-size: clamp(26px, 3vw, 34px);
			font-weight: 700;
			line-height: 1.08;
			letter-spacing: 0;
			text-align: left;
		}

		body#corpo h1 span {
			display: block;
			margin-top: 7px;
			color: var(--mural-muted);
			font-size: 15px;
			font-weight: 500;
			line-height: 1.4;
		}

		.periodo-form {
			display: flex;
			flex-wrap: wrap;
			align-items: center;
			gap: 8px;
			margin: 0;
		}

		.periodo-form label {
			color: var(--mural-muted);
			font-size: 13px;
			font-weight: 600;
		}

		.periodo-form select {
			min-height: 40px;
			padding: 0 12px;
			border: 1px solid var(--mural-line-strong);
			border-radius: 6px;
			background: var(--mural-surface);
			color: var(--mural-ink);
			font: inherit;
			font-size: 14px;
		}

		.notice {
			margin: 0 0 14px !important;
			padding: 12px 14px;
			border: 1px solid var(--mural-line);
			border-left: 4px solid var(--mural-accent);
			border-radius: 0 6px 6px 0;
			background: var(--mural-surface) !important;
			color: var(--mural-muted);
			font-size: 14px;
			line-height: 1.55;
			text-indent: 0 !important;
			text-align: left !important;
		}

		.notice a {
			color: var(--mural-accent);
			font-weight: 600;
			text-decoration: none;
		}

		.notice a:hover { text-decoration: underline; }

		.summary-strip {
			display: flex;
			flex-wrap: wrap;
			gap: 8px 24px;
			margin: 0 0 16px;
			padding: 12px 16px;
			border: 1px solid var(--mural-line);
			border-radius: 8px;
			background: var(--mural-surface);
		}

		.metric {
			display: inline-flex;
			align-items: baseline;
			gap: 6px;
			color: var(--mural-muted);
			font-size: 13px;
			line-height: 1.4;
		}

		.metric strong {
			color: var(--mural-ink);
			font-size: 20px;
			font-weight: 700;
			line-height: 1;
		}

		.table-shell {
			width: 100%;
			padding: 14px 16px 16px;
			border: 1px solid var(--mural-line);
			border-radius: 8px;
			background: var(--mural-surface);
		}

		.table-shell .dataTables_wrapper { width: 100%; }

		#mural {
			width: 100% !important;
			border-collapse: collapse !important;
			background: var(--mural-surface) !important;
			color: var(--mural-ink);
			font-size: 14px !important;
		}

		#mural thead th {
			padding: 11px 12px !important;
			border: 0 !important;
			border-bottom: 1px solid var(--mural-line-strong) !important;
			background: var(--mural-soft) !important;
			color: var(--mural-ink) !important;
			font-size: 11px !important;
			font-weight: 700 !important;
			line-height: 1.4;
			letter-spacing: 0.04em;
			text-align: left !important;
			text-transform: uppercase;
			white-space: nowrap;
		}

		#mural thead th.coluna_centralizada { text-align: center !important; }

		#mural tbody td {
			padding: 12px !important;
			border: 0 !important;
			border-bottom: 1px solid #EDF2F5 !important;
			background: var(--mural-surface) !important;
			color: var(--mural-ink);
			font-size: 13px !important;
			line-height: 1.45;
			vertical-align: middle;
		}

		#mural tbody tr { background: var(--mural-surface) !important; }
		#mural tbody tr:hover td { background: #F8FBFD !important; }
		#mural a { color: var(--mural-accent); font-weight: 600; text-decoration: none; }
		#mural a:hover { text-decoration: underline; }
		#mural .coluna_centralizada { text-align: center; }

		.inst-name { font-weight: 600; }

		.convenio-badge {
			display: inline-flex;
			align-items: center;
			margin-left: 7px;
			padding: 2px 6px;
			border: 1px solid transparent;
			border-radius: 999px;
			font-family: "IBM Plex Mono", Consolas, "Liberation Mono", monospace;
			font-size: 9px;
			font-weight: 500;
			letter-spacing: 0.04em;
			line-height: 1.4;
			text-transform: uppercase;
			vertical-align: middle;
			white-space: nowrap;
		}

		.convenio-badge.sim {
			color: var(--mural-success);
			background: var(--mural-success-soft);
			border-color: #B9DCC5;
		}

		.convenio-badge.nao {
			color: var(--mural-warning);
			background: var(--mural-warning-soft);
			border-color: #E8CFA8;
		}

		.empty-state {
			padding: 28px 16px !important;
			color: var(--mural-muted);
			font-size: 14px;
			text-align: center !important;
		}

		.flash-message {
			margin-bottom: 16px;
			padding: 12px 14px;
			border: 1px solid #B9DCC5;
			border-radius: 8px;
			background: var(--mural-success-soft);
			color: var(--mural-success);
			font-size: 14px;
			font-weight: 600;
			line-height: 1.5;
		}

		.dataTables_wrapper .dataTables_length,
		.dataTables_wrapper .dataTables_filter {
			margin-bottom: 12px;
			color: var(--mural-muted);
			font-size: 13px;
		}

		.dataTables_wrapper .dataTables_filter input,
		.dataTables_wrapper .dataTables_length select {
			min-height: 36px;
			padding: 0 10px;
			border: 1px solid var(--mural-line-strong);
			border-radius: 6px;
			background: var(--mural-surface);
			color: var(--mural-ink);
			font: inherit;
		}

		.dataTables_wrapper .dataTables_info,
		.dataTables_wrapper .dataTables_paginate {
			margin-top: 14px;
			color: var(--mural-muted);
			font-size: 12px;
		}

		.dataTables_wrapper .dataTables_paginate .paginate_button {
			min-width: 32px;
			min-height: 32px;
			border-radius: 4px !important;
		}

		#mural_wrapper .dt-layout-row {
			align-items: center;
			gap: 16px;
			margin: 0 0 10px !important;
		}

		#mural_wrapper .dt-layout-cell { padding: 0 !important; }

		#mural_wrapper .dt-length,
		#mural_wrapper .dt-search,
		#mural_wrapper .dt-info,
		#mural_wrapper .dt-paging {
			color: var(--mural-muted);
			font-size: 12px;
			line-height: 1.4;
		}

		#mural_wrapper .dt-search { text-align: right; }

		#mural_wrapper .dt-search input,
		#mural_wrapper .dt-length select {
			min-height: 36px;
			padding: 0 10px;
			border: 1px solid var(--mural-line-strong);
			border-radius: 6px;
			background: var(--mural-surface);
			color: var(--mural-ink);
			font: inherit;
			font-size: 13px;
		}

		#mural_wrapper .dt-search input {
			width: min(240px, 42vw);
			margin-left: 8px;
		}

		#mural_wrapper .dt-info { margin-top: 14px; }
		#mural_wrapper .dt-paging { margin: 0; text-align: right; }
		#mural_wrapper .dt-paging .dt-paging-button { min-width: 32px; min-height: 32px; border-radius: 4px !important; }

		.sr-only {
			position: absolute;
			width: 1px;
			height: 1px;
			padding: 0;
			margin: -1px;
			overflow: hidden;
			clip: rect(0, 0, 0, 0);
			white-space: nowrap;
			border: 0;
		}

		body#corpo a:focus-visible,
		body#corpo select:focus-visible,
		body#corpo input:focus-visible,
		body#corpo button:focus-visible {
			outline: 3px solid var(--mural-focus);
			outline-offset: 2px;
		}

		@media (max-width: 820px) {
			body#corpo { padding: 18px 14px 28px; }
			.page-head { align-items: flex-start; flex-direction: column; }
			body#corpo h1 { font-size: 26px; }
			.periodo-form { width: 100%; }
			.periodo-form label { width: 100%; }
			.periodo-form select { flex: 1; }
			.summary-strip { gap: 8px 18px; }
			.table-shell { overflow-x: auto; padding: 12px; }
			#mural { min-width: 760px; }
		}
	</style>
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

		<p class="notice">
			Consulte <a href="http://www.pr1.ufrj.br/estagios/busca.php" target="_blank" rel="noopener noreferrer">as instituições conveniadas com a UFRJ</a>
			e selecione o curso de Serviço Social no catálogo de estágios.
		</p>

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
