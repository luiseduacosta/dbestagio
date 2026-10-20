<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ver cada aluno</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

{literal}
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jquery.maskedinput-1.2.1.pack.js"></script>
<script type="text/javascript">
	$(function () {
		$("#telefone").mask("(99)9999.9999");
		$("#celular").mask("(99)99999.9999");
		$("#cep").mask("99999-999");
		$("#cpf").mask("999999999-99");
	});
</script>

<script type="text/javascript">
	function get_periodo() {
		var periodo = document.getElementById('periodo').value;
		window.location = 'ver_cada.php?periodo=' + encodeURIComponent(periodo) + '&indice=0';
	}
</script>
{/literal}

</head>
<body id="corpo">

<main class="mural-page">

	{if $isAdmin}
	<table id="navegacao" class="toolbar">
		<tbody>
			<tr>
				<td>
					<form action="#" method="post">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="botao" value="primeiro">
						<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
						<input type="hidden" name="aluno_id" value="">
						<input type="submit" name="submit" value="Primeiro">
					</form>
				</td>
				<td>
					<form action="#" method="post">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="botao" value="menos_10">
						<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
						<input type="hidden" name="aluno_id" value="">
						<input type="submit" name="submit" value=" - 10 ">
					</form>
				</td>
				<td>
					<form action="#" method="post">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="botao" value="retroceder">
						<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
						<input type="hidden" name="aluno_id" value="">
						<input type="submit" name="submit" value="Retroceder">
					</form>
				</td>
				<td>
					<form action="#" method="post">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="botao" value="avancar">
						<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
						<input type="hidden" name="aluno_id" value="">
						<input type="submit" name="submit" value="Avançar">
					</form>
				</td>
				<td>
					<form action="#" method="post">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="botao" value="mais_10">
						<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
						<input type="hidden" name="aluno_id" value="">
						<input type="submit" name="submit" value=" + 10 ">
					</form>
				</td>
				<td>
					<form action="#" method="post">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="botao" value="ultimo">
						<input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
						<input type="hidden" name="aluno_id" value="">
						<input type="submit" name="submit" value="Último">
					</form>
				</td>
			</tr>
		</tbody>
	</table>
	{/if}

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Alunos</p>
			<h1>Aluno(a): {$nome}<span>{if $registro}DRE {$registro}{else}Visualizar aluno{/if}</span></h1>
		</div>

		{if $isAdmin}
		<form class="periodo-form">
			<label for="periodo">Período</label>
			<select name="periodo" id="periodo" size="1" onChange="get_periodo();">
				{if !$periodo}
				<option value="0">Seleciona período</option>
				{else}
				<option value="0">Período: {$periodo}</option>
				{/if}
				{section name=i loop=$periodos}
				<option value="{$periodos[i]}">{$periodos[i]}</option>
				{/section}
			</select>
		</form>
		{/if}
	</header>

	{if $isAdmin || $isAluno}
	<div class="action-row" style="margin-top:0;">
		<a class="action-btn" href="atualizar.php?aluno_id={$aluno_id}">Editar aluno(a)</a>
		{if $isAdmin}
		<a class="btn btn-danger" href="excluir.php?aluno_id={$aluno_id}" onclick="return confirm('Excluir este aluno?');">Excluir</a>
		{/if}
	</div>
	{/if}

	<table class="ficha card-table">
		<tbody>
			{* ###CORPO### *}
			{if $smarty.cookies.usuario}
			<tr>
				<th>Registro</th>
				<td>{$registro}</td>
			</tr>
			{/if}

			<tr>
				<th>Nome</th>
				<td>{$nome}</td>
			</tr>

			<tr>
				<th>Ingresso</th>
				{if !$periodo_intro}
				<td>s/d</td>
				{else}
				<td>{$periodo_intro}&nbsp; - Período atual: {$tempo_cursado}o.</td>
				{/if}
			</tr>

			<tr>
				<th>Turno</th>
				<td>{$turno}</td>
			</tr>

			{if $smarty.cookies.usuario}
			<tr>
				<th>E-mail</th>
				<td>{$email}</td>
			</tr>

			<tr>
				<th>Telefone</th>
				<td>({$codigo_telefone}){$telefone}</td>
			</tr>

			<tr>
				<th>Celular</th>
				<td>({$codigo_celular}){$celular}</td>
			</tr>

			<tr>
				<th>Observa&ccedil;&otilde;es</th>
				<td>{$observacoes}</td>
			</tr>
			{/if}
		</tbody>
	</table>

	<section class="table-shell" aria-label="Histórico de estágios do aluno">
		<table class="dt-table">
			<thead>
				<tr>
					{if $isAdmin}
					<th>Editar</th>
					{/if}
					<th>Período</th>
					<th>TC</th>
					<th>Nível</th>
					<th>Instituição</th>
					<th>Supervisor</th>
					<th>Professor</th>
					{if $isAdmin}
					<th>Nota</th>
					<th>ch</th>
					{/if}
				</tr>
			</thead>
			<tbody>
				{section name=estagio loop=$historico_estagio}
				<tr>
					{if $isAdmin}
					<td>
						<span class="action-links">
						<a href="atualizar/atualiza_estagio.php?estagiario_id={$historico_estagio[estagio].estagiario_id}&aluno_id={$aluno_id}">Editar</a>
						</span>
					</td>
					{/if}
					<td class="coluna_centralizada">{$historico_estagio[estagio].periodo}</td>
					<td class="coluna_centralizada">{$historico_estagio[estagio].tc}</td>
					<td class="coluna_centralizada">{$historico_estagio[estagio].nivel}</td>
					<td>
						{if $isAdmin}
						<a href="../instituicoes/ver_cada.php?instituicao_id={$historico_estagio[estagio].instituicao_id}">{$historico_estagio[estagio].instituicao}</a>
						{else}
						{$historico_estagio[estagio].instituicao}
						{/if}
					</td>

					{if $historico_estagio[estagio].supervisor_id eq 0}
					<td>-</td>
					{else}
					<td>
						{if $isAdmin}
						<a href="../supervisores/ver_cada.php?supervisor_id={$historico_estagio[estagio].supervisor_id}">{$historico_estagio[estagio].supervisor}</a>
						{else}
						{$historico_estagio[estagio].supervisor}
						{/if}
					</td>
					{/if}

					{if $historico_estagio[estagio].professor_id eq 0}
					<td>-</td>
					{else}
					<td>
						{if $isAdmin}
						<a href="../professores/ver_cada.php?professor_id={$historico_estagio[estagio].professor_id}">{$historico_estagio[estagio].professor}</a>
						{else}
						{$historico_estagio[estagio].professor}
						{/if}
					</td>
					{/if}

					{if $isAdmin}
					<td class="coluna_centralizada">{$historico_estagio[estagio].nota}</td>
					<td class="coluna_centralizada">{$historico_estagio[estagio].ch}</td>
					{/if}
				</tr>
				{/section}
			</tbody>
		</table>
	</section>

	{if $isAdmin}
	<div class="action-row">
		<a class="btn btn-secondary" href="listar.php">Voltar para a listagem</a>
	</div>
	{/if}

</main>

</body>
</html>
