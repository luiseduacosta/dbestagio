<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Instituição: {$instituicao}</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

{literal}
<script type="text/javascript">
function elimina() {
	return confirm("Tem certeza?");
}
</script>
{/literal}

</head>
<body id="corpo">

<main class="mural-page">

	{* Barra de navegação: exclusiva do administrador (inserir/excluir e
	   navegação registro a registro). Professor apenas consulta a ficha. *}
	{if $isAdmin}
	<table id="navegacao" class="toolbar">
	<tbody>
	<tr>

	<td class="is-danger">
	<form action="ver_cada.php" method="post" onsubmit="return elimina();">
	<input type="hidden" name="botao" value="inserir">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" name="inserir" value="Inserir">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="post">
	<input type="hidden" name="botao" value="primeiro">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" value="Primeiro">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="post">
	<input type="hidden" name="botao" value="menos_10">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" value="- 10">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="post">
	<input type="hidden" name="botao" value="menos_1">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" value="- 1">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="post">
	<input type="hidden" name="botao" value="mais_1">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" value="+ 1">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="post">
	<input type="hidden" name="botao" value="mais_10">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" value="+ 10">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="post">
	<input type="hidden" name="botao" value="ultimo">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" value="Último">
	</form>
	</td>

	<td class="is-danger">
	<form action="excluir.php" method="post" onsubmit="return elimina();">
	<input type="hidden" name="botao" value="excluir">
	{if $id}
	<input type="hidden" name="instituicao_id" value="{$id}">
	{else}
	<input type="hidden" name="instituicao_id" value="{$instituicao_id}">
	{/if}
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" value="Excluir">
	</form>
	</td>

	<td>
	<form action="../visitas/inserir.php" method="get">
	<input type="hidden" name="instituicao_id" value="{$instituicao_id}">
	<input type="submit" value="Adicionar visita">
	</form>
	</td>

	</tr>
	</tbody>
	</table>
	{/if}

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Instituições</p>
			<h1>{$instituicao}{if $area}<span>{$area}{if $natureza} · {$natureza}{/if}</span>{/if}</h1>
		</div>
	</header>

	{if $flash}<p class="flash-message">{$flash}</p>{/if}

	{* Instituição: formulário de edição inline (admin) ou ficha somente-leitura *}
	{if $isAdmin}
	<form name="modifica_instituicao" action="?instituicao_id={$instituicao_id}" method="post">
	{/if}

	<table class="ficha card-table">
	<tbody>

		<tr>
			<th>Instituição</th>
			<td>
				{if $modifica}
					<input type="text" name="instituicao" size="50" maxlength="100" value="{$instituicao}">
				{else}
					{if $id_curso_instituicao}
						<a href="ver_cada.php?curso=sem&instituicao_id={$id_curso_instituicao}">{$instituicao}</a>
					{else}
						{$instituicao}
					{/if}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Página web</th>
			<td>
				{if $modifica}
					<input type="text" name="url" size="50" maxlength="100" value="{$url}">
				{else}
					{if $url}<a href="{$url}" target="_blank" rel="noopener">{$url|truncate:50}</a>{else}—{/if}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Endereço</th>
			<td>
				{if $modifica}
					<input type="text" name="endereco" size="50" value="{$endereco}">
				{else}
					{$endereco}
				{/if}
			</td>
		</tr>

		<tr>
			<th>CEP</th>
			<td>
				{if $modifica}
					<input type="text" name="cep" size="9" value="{$cep}">
				{else}
					{$cep}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Bairro</th>
			<td>
				{if $modifica}
					<input type="text" name="bairro" size="30" value="{$bairro}">
				{else}
					{$bairro}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Município</th>
			<td>
				{if $modifica}
					<input type="text" name="municipio" size="30" value="{$municipio}">
				{else}
					{$municipio}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Telefone</th>
			<td>
				{if $modifica}
					<input type="text" name="telefone" size="35" value="{$telefone}">
				{else}
					{$telefone}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Benefícios</th>
			<td>
				{if $modifica}
					<input type="text" name="beneficios" size="50" value="{$beneficios}">
				{else}
					{$beneficios}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Estágio no final de semana</th>
			<td>
				{if $modifica}
					{if $fim_de_semana == 0}
						<input type="radio" name="fim_de_semana" value="0" checked>Não
						<input type="radio" name="fim_de_semana" value="1">Sim
						<input type="radio" name="fim_de_semana" value="2">Parcialmente
					{elseif $fim_de_semana == 1}
						<input type="radio" name="fim_de_semana" value="0">Não
						<input type="radio" name="fim_de_semana" value="1" checked>Sim
						<input type="radio" name="fim_de_semana" value="2">Parcialmente
					{else}
						<input type="radio" name="fim_de_semana" value="0">Não
						<input type="radio" name="fim_de_semana" value="1">Sim
						<input type="radio" name="fim_de_semana" value="2" checked>Parcialmente
					{/if}
				{else}
					{if $fim_de_semana eq 0}Não
					{elseif $fim_de_semana eq 1}Sim
					{elseif $fim_de_semana eq 2}Parcialmente
					{else}—
					{/if}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Turma</th>
			<td>
				{if $modifica}
					Campo calculado automaticamente
				{else}
					{if $turma}
						<a href="../alunos/listar.php?seleciona_instituicao={$id}&seleciona_periodo={$turma}">{$turma}</a>
					{else}
						—
					{/if}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Área</th>
			<td>
				{if $modifica}
					<select name="area" size="1">
						<option value='{$id_area}' selected>{$area}</option>
						{section name=elementos loop=$matriz_areas}
							<option value='{$matriz_areas[elementos].id}'>{$matriz_areas[elementos].area}</option>
						{/section}
					</select>
				{else}
					{if $id_area}
						<a href="../areas/instituicoes.php?id_area={$id_area}">{$area}</a>
					{else}
						—
					{/if}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Natureza</th>
			<td>
				{if $modifica}
					<input type="text" name="natureza" size="15" value="{$natureza}">
				{else}
					{$natureza}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Convênio UFRJ</th>
			<td>
				{if $modifica}
					<input type="text" name="convenio" id="id_convenio" size="5" value="{$convenio}">
				{else}
					{if $convenio != 0}
						<a href="http://www.pr1.ufrj.br/estagios/info.php?codEmpresa={$convenio}">{$convenio}</a>
					{else}
						{$convenio}
					{/if}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Seguro</th>
			<td>
				{if $modifica}
					{if $seguro eq 0}
						<input type="radio" name="seguro" id="id_seguro" value="0" checked>Instituição
						<input type="radio" name="seguro" id="id_seguro" value="1">UFRJ
					{else}
						<input type="radio" name="seguro" id="id_seguro" value="0">Instituição
						<input type="radio" name="seguro" id="id_seguro" value="1" checked>UFRJ
					{/if}
				{else}
					{if $seguro eq 0}Instituição{else}UFRJ{/if}
				{/if}
			</td>
		</tr>

		<tr>
			<th>Observações</th>
			<td>
				{if $modifica}
					<textarea rows="4" cols="60" name="observacoes">{$observacoes}</textarea>
				{else}
					{$observacoes}
				{/if}
			</td>
		</tr>

	</tbody>
	</table>

	<div class="action-row">
		{if $isAdmin}
			<input type="hidden" name="flag" value="{$flag}">
			<input type="hidden" name="indice" value="{$indice}">
			{if $id}
				<input type="hidden" name="instituicao_id" value="{$id}">
			{else}
				<input type="hidden" name="instituicao_id" value="{$instituicao_id}">
			{/if}
			<button type="submit" name="modifica" class="btn">{if $modifica}Salvar alterações{else}Modificar instituição{/if}</button>
		{/if}
		<a class="btn btn-secondary" href="listar.php">Voltar para a listagem</a>
	</div>

	{if $isAdmin}
	</form>
	{/if}

	{* Supervisores vinculados *}
	{if !empty($inst_supervisores)}
	<section class="table-shell" aria-label="Supervisores da instituição">
		<p class="eyebrow">Supervisores</p>
		<table class="dt-table">
		<thead>
		<tr>
			<th>CRESS</th>
			<th>Nome</th>
			{if $isAdmin}<th data-orderable="false">Ações</th>{/if}
		</tr>
		</thead>
		<tbody>
		{section name=i loop=$inst_supervisores}
			<tr>
				<td class="coluna_direita">{$inst_supervisores[i].cress}</td>
				<td><a href="../supervisores/ver_cada.php?supervisor_id={$inst_supervisores[i].supervisor_id}">{$inst_supervisores[i].nome}</a></td>
				{if $isAdmin}
				<td class="action-links">
					<a href="../supervisores/ver_cada.php?supervisor_id={$inst_supervisores[i].supervisor_id}">Modifica</a>
					<a class="is-danger" href="../supervisores/inst_supervisor.php?supervisor_id={$inst_supervisores[i].supervisor_id}&instituicao_id={$id}" onclick="return confirm('Desvincular este supervisor da instituição?');">Excluir</a>
				</td>
				{/if}
			</tr>
		{/section}
		</tbody>
		</table>
	</section>
	{/if}

	{* Vincular supervisor: exclusivo do administrador *}
	{if $isAdmin}
	<section class="table-shell" aria-label="Vincular supervisor">
		<form name="supervisores" id="supervisores" action="ver_cada.php" method="post" class="periodo-form">
			<label for="supervisor_id">Vincular supervisor</label>
			<select name="supervisor_id" id="supervisor_id" size="1">
				<option value="0">Selecione supervisor para inserir na instituição</option>
				{section name=i loop=$supervisores}
					<option value="{$supervisores[i].id}">{$supervisores[i].nome}</option>
				{/section}
			</select>
			<input type="hidden" name="instituicao_id" value="{$id}">
			<button type="submit" class="btn btn-sm">Inserir supervisor</button>
		</form>
	</section>
	{/if}

	{* Professores que orientam estagiários nesta instituição *}
	{if !empty($inst_professores)}
	<section class="table-shell" aria-label="Professores da instituição">
		<p class="eyebrow">Professores</p>
		<table class="dt-table">
		<thead>
		<tr>
			<th>Nome</th>
			<th class="coluna_centralizada">Último período</th>
		</tr>
		</thead>
		<tbody>
		{section name=i loop=$inst_professores}
			<tr>
				<td>{$inst_professores[i].nome}</td>
				<td class="coluna_centralizada">{$inst_professores[i].periodo}</td>
			</tr>
		{/section}
		</tbody>
		</table>
	</section>
	{/if}

</main>

</body>
</html>
