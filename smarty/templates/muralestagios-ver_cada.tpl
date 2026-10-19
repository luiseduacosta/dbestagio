<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>{$instituicao[0].instituicao}</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="author" content="Luis Acosta">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<style type="text/css">
		@import url("../estagio.css");
	</style>
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

<body>

	<p><a href="javascript:history.back(1)">Voltar</a></p>

	<div align="center">
		{if $sistema_autentica == 1}
		<span class="botao">
			<a href="mural-imprime_cartaz.php?muralestagio_id={$muralestagio_id}">Imprimir cartaz</a>
		</span>
		{/if}


		{if $data_inscricao == 0}
		<p style="text-align:center">Inscrições diretamente na instituição</p>
		{else}
		{if $data_hoje > $data_inscricao}
		<p style="text-align:center">Inscrições encerradas</p>
		{else}
		<p style="text-align:center">Inscrições abertas</p>
		{/if}
		{/if}

		{if $pode_inscrever}
		<p style="text-align:center">
			<span class="botao"><a href="../inscricoes/inserir.php?muralestagio_id={$muralestagio_id}">Inscrição</a></span>
		</p>
		{/if}

		<!--
{if $data_selecao == "00000000"}
	<p style="text-align:center">Sem data de seleção</p>
{else}
	{if $data_hoje > $data_selecao}
	    <p style="text-align:center">Seleção já realizada ou em processo</p>
	{/if}
{/if}
//-->
	</div>

	{if $sistema_autentica == 1}
	<div align="center">
		<table border="0">
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
	</div>
	{/if}

	{section name=item loop=$instituicao}

	<div align="center">
		<table border="1" width="95%">
			<tbody>

				{if $instituicao[item].convenio == 0}
				<tr style="background-color:#fdb9b9">
					{else}
				<tr style="background-color:#c9f5bf">
					{/if}
					<td>Convênio</td>
					<td>{$instituicao[item].convenio}</td>
				</tr>

				<tr>
				<td width="30%">Instituição</td>
				{if $instituicao[item].instituicao_id == 0 || $sistema_autentica != 1}
				<td>{$instituicao[item].instituicao}</td>
				{else}
				<td><a
						href="../instituicoes/ver_cada.php?instituicao_id={$instituicao[item].instituicao_id}">{$instituicao[item].instituicao}</a>
				</td>
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

				{if $data_inscricao == 0}
				<tr>
					<td>Inscrições na Coordenação de Estágio até</td>
					<td>Inscrições diretamente na instituição</td>
				</tr>
				{else}
				<tr>
					<td>Inscrições na Coordenação de Estágio até</td>
					<td>{$instituicao[item].data_inscricao}</td>
				</tr>
				{/if}

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

				{if $sistema_autentica == 1}
				<tr style="background-color:#e7e1ae;">
					<td>Email</td>
					{if $instituicao[item].email == ""}
					<td style="text-decoration:blink;">Sem e-mail</td>
					{else}
					<td>{$instituicao[item].email}</td>
					{/if}
				</tr>

				<tr style="background-color:#e7e1ae;">
					<td>Email enviado em:</td>
					{if $data_hoje > $data_inscricao}
					{if $instituicao[item].datafax == 0}
					<td style="background-color:red;">Email ainda não enviado</td>
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

				<tr>
				<td colspan="2" class="coluna_centralizada">

					{* --- Botões restritos a usuários autenticados --- *}

					{if $sistema_autentica == 1}
					<span class="botao">
						<a href="mural-modifica.php?muralestagio_id={$muralestagio_id}">Editar</a>
					</span>
					{/if}

					{* Inscrição: admin sempre; aluno só se pode_inscrever (data ok) *}
					{if $pode_inscrever}
					<span class="botao">
						<a href="../inscricoes/inserir.php?muralestagio_id={$muralestagio_id}">Inscrição para seleção</a>
					</span>
					{/if}

					{* Alunos inscritos: só para coordenação (admin/professor/supervisor) *}
					{if $sistema_autentica == 1 && !$is_aluno}
					<span class="botao">
						<a href="inscritos.php?muralestagio_id={$muralestagio_id}">Alunos inscritos</a>
					</span>
					{/if}

					{* Excluir: só admin *}
					{if $isAdmin}
					<span class="botao">
						<a href="mural-excluir.php?muralestagio_id={$muralestagio_id}">Excluir</a>
					</span>
					{/if}

				</td>
			</tr>
			{/section}

			</tbody>
		</table>
	</div>

</body>

</html>