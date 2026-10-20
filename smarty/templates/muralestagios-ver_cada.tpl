<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="color-scheme" content="light">
	<title>{if $instituicao}{$instituicao[0].instituicao}{else}Mural de estágios{/if} — Estágio ESS/UFRJ</title>
	<link rel="stylesheet" href="../estagio.css">
	<link rel="stylesheet" href="../estagio-moderno.css">
	{literal}
	<script>
		function barra() {
			var valor = document.getElementById("barra_indice").value;
			window.location = "mural-ver_cada.php?indice=" + valor;
			return true;
			// alert("Hola" + valor);
		}
	</script>
	{/literal}
</head>

<body id="corpo">

<main class="mural-page">

	{* Navegação entre ofertas: apenas perfis autenticados que não sejam aluno *}
	{if $sistema_autentica == 1 && !$is_aluno}
	<table id="navegacao" class="toolbar" aria-label="Navegação entre ofertas de estágio">
		<tbody>
			<tr>
				<td>
					<form name="cabecalho" action="#" method="post">
						<input type="hidden" name="botao" value="primeiro">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="muralestagio_id" value="{$muralestagio_id}">
						<input type="submit" name="submit" value="Primeiro">
					</form>
				</td>
				<td>
					<form name="cabecalho" action="#" method="post">
						<input type="hidden" name="botao" value="menos_10">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="muralestagio_id" value="{$muralestagio_id}">
						<input type="submit" name="submit" value="- 10">
					</form>
				</td>
				<td>
					<form name="cabecalho" action="#" method="post">
						<input type="hidden" name="botao" value="menos_1">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="muralestagio_id" value="{$muralestagio_id}">
						<input type="submit" name="submit" value="- 1">
					</form>
				</td>
				<td>
					<form name="cabecalho" action="#" method="post">
						<input type="hidden" name="botao" value="mais_1">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="muralestagio_id" value="{$muralestagio_id}">
						<input type="submit" name="submit" value="+ 1">
					</form>
				</td>
				<td>
					<form name="cabecalho" action="#" method="post">
						<input type="hidden" name="botao" value="mais_10">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="muralestagio_id" value="{$muralestagio_id}">
						<input type="submit" name="submit" value="+ 10">
					</form>
				</td>
				<td>
					<form name="cabecalho" action="#" method="post">
						<input type="hidden" name="botao" value="ultimo">
						<input type="hidden" name="indice" value="{$indice}">
						<input type="hidden" name="muralestagio_id" value="{$muralestagio_id}">
						<input type="submit" name="submit" value="Último">
					</form>
				</td>
			</tr>
		</tbody>
	</table>
	{/if}

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio e Extensão · ESS/UFRJ</p>
			<h1>Oferta de estágio<span>{if $instituicao}{$instituicao[0].instituicao}{else}Sem oferta{/if}</span></h1>
		</div>

		<div class="action-row" style="margin-top:0;">
			<a class="btn btn-secondary" href="javascript:history.back(1)">Voltar</a>
			{* Imprimir cartaz: exclusivo do admin *}
			{if $isAdmin}
			<a class="btn" href="mural-imprime_cartaz.php?muralestagio_id={$muralestagio_id}">Imprimir cartaz</a>
			{/if}
		</div>
	</header>

	<p class="card-note">
		{if $data_inscricao == 0}
		Inscrições diretamente na instituição
		{elseif $data_hoje > $data_inscricao}
		Inscrições encerradas
		{else}
		Inscrições abertas
		{/if}
	</p>

	{* Inscrição: o aluno vê um único botão, aberto ou encerrado, conforme o
	   prazo (data_inscricao x hoje); os demais perfis mantêm o comportamento
	   anterior (somente o admin recebe pode_inscrever). *}
	{if $is_aluno}
	<div class="action-row" style="margin-top:0;">
		{if $data_inscricao != 0 && $data_hoje <= $data_inscricao}
		<a class="btn" href="../inscricoes/inserir.php?muralestagio_id={$muralestagio_id}">Inscrição</a>
		{else}
		<a class="btn btn-secondary" href="javascript:void(0);" onclick="return false;" aria-disabled="true" tabindex="-1">Inscrições encerradas</a>
		{/if}
	</div>
	{elseif $pode_inscrever}
	<div class="action-row" style="margin-top:0;">
		<a class="btn" href="../inscricoes/inserir.php?muralestagio_id={$muralestagio_id}">Inscrição</a>
	</div>
	{/if}

	{if $instituicao}
	<table class="ficha">
		<tbody>
			{section name=item loop=$instituicao}
			<tr>
				<td>Convênio</td>
				<td>
					<span class="convenio-badge {if $instituicao[item].convenio == 0}nao{else}sim{/if}">{if $instituicao[item].convenio == 0}Não conveniada{else}Conveniada{/if}</span>
				</td>
			</tr>

			<tr>
				<td>Instituição</td>
				{* Link para a ficha da instituição: oculto para o aluno (só a
				   coordenação acessa o cadastro de instituições). *}
				{if $instituicao[item].instituicao_id == 0 || $sistema_autentica != 1 || $is_aluno}
				<td>{$instituicao[item].instituicao}</td>
				{else}
				<td><a href="../instituicoes/ver_cada.php?instituicao_id={$instituicao[item].instituicao_id}">{$instituicao[item].instituicao}</a></td>
				{/if}
			</tr>

			<tr>
				<td>Vagas</td>
				<td>{$instituicao[item].vagas}</td>
			</tr>

			<tr>
				<td>Benefícios</td>
				<td>{$instituicao[item].beneficios}</td>
			</tr>

			<tr>
				<td>Final de semana</td>
				<td>{$instituicao[item].final_de_semana}</td>
			</tr>

			<tr>
				<td>Carga horária</td>
				<td>{$instituicao[item].carga_horaria}</td>
			</tr>

			<tr>
				<td>Requisitos</td>
				<td>{$instituicao[item].requisitos}</td>
			</tr>

			<tr>
				<td>Área</td>
				<td>{$instituicao[item].area}</td>
			</tr>

			<tr>
				<td>Professor</td>
				<td>{$instituicao[item].professor}</td>
			</tr>

			<tr>
				<td>Horário</td>
				<td>{$instituicao[item].horario}</td>
			</tr>

			<tr>
				<td>Inscrições na Coordenação de Estágio até</td>
				{if $data_inscricao == 0}
				<td>Inscrições diretamente na instituição</td>
				{else}
				<td>{$instituicao[item].data_inscricao}</td>
				{/if}
			</tr>

			<tr>
				<td>Data e horário da seleção</td>
				<td>
					{if !$instituicao[item].tem_selecao}
					Sem data de seleção
					{else}
					{$instituicao[item].data_selecao}: Horário: {$instituicao[item].horario_selecao} hs.
					{/if}
				</td>
			</tr>

			<tr>
				<td>Local da seleção</td>
				<td>{$instituicao[item].local_selecao}</td>
			</tr>

			<tr>
				<td>Forma de seleção</td>
				<td>{$instituicao[item].forma_selecao}</td>
			</tr>

			<tr>
				<td>Contatos e/ou informações</td>
				<td>{$instituicao[item].contato}</td>
			</tr>

			{* Dados de contato por e-mail: somente usuários autenticados *}
			{if $sistema_autentica == 1}
			<tr>
				<td>Email</td>
				<td>{if $instituicao[item].email == ""}Sem e-mail{else}{$instituicao[item].email}{/if}</td>
			</tr>

			<tr>
				<td>Email enviado em</td>
				{if $data_hoje > $data_inscricao}
				{if $instituicao[item].datafax == 0}
				<td>Email ainda não enviado</td>
				{else}
				<td>{$instituicao[item].datafax|date_format:"%d-%m-%Y"}</td>
				{/if}
				{else}
				<td>Inscrições abertas</td>
				{/if}
			</tr>
			{/if}

			<tr>
				<td>Outras informações</td>
				<td>{$instituicao[item].outras}</td>
			</tr>
			{/section}
		</tbody>
	</table>
	{else}
	<p class="empty-state">Nenhuma oferta de estágio encontrada.</p>
	{/if}

	{* Ações da coordenação: o aluno não vê esta linha. Editar e Excluir são
	   exclusivos do admin; Alunos inscritos é da coordenação. *}
	{if $sistema_autentica == 1 && !$is_aluno}
	<div class="action-row">
		{if $isAdmin}
		<a class="action-btn" href="mural-modifica.php?muralestagio_id={$muralestagio_id}">Editar</a>
		{/if}
		{if $pode_inscrever}
		<a class="btn" href="../inscricoes/inserir.php?muralestagio_id={$muralestagio_id}">Inscrição para seleção</a>
		{/if}
		<a class="btn btn-secondary" href="inscritos.php?muralestagio_id={$muralestagio_id}">Alunos inscritos</a>
		{if $isAdmin}
		<a class="btn btn-danger" href="mural-excluir.php?muralestagio_id={$muralestagio_id}" onclick="return confirm('Excluir esta oferta?');">Excluir</a>
		{/if}
	</div>
	{/if}

</main>

</body>

</html>
