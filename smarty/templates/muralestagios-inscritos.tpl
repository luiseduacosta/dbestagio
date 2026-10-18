<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Alunos inscritos no mural</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="description" content="Alunos inscritos nas ofertas do mural de estágios">
	<link rel="stylesheet" type="text/css" href="../estagio.css">
	<link rel="stylesheet" type="text/css" href="../lib/mygosumenu/1.0/example1.css">
	<link rel="stylesheet" type="text/css" href="../libjs/datatables/dataTables.min.css">
	<script type="text/javascript" src="../lib/mygosumenu/ie5.js"></script>
	<script type="text/javascript" src="../lib/mygosumenu/1.0/DropDownMenu1.js"></script>
</head>

<body style="direction: ltr;">

{if $sistema_autentica == 1}
	{include file="muralestagios-menu.tpl"}
{/if}

<h1>Alunos inscritos no mural{if $periodo != ''} - Turma {$periodo}{else} - todos os períodos{/if}</h1>

{if $instituicao != ''}
	<h2 style="text-align:center">Oferta: {$instituicao} ({$periodo})</h2>
{/if}

<div align="center">
	<h1>São {$totalAlunos} alunos listados</h1>
</div>

<form name="filtro_inscritos" method="get" action="inscritos.php" style="text-align:center">

	{* O seletor de período é exclusivo do admin; os demais perfis sempre veem
	   o período atual e o parâmetro nem é enviado. *}
	{if $is_admin}
	<label for="periodo">Período:</label>
	<select name="periodo" id="periodo" onChange="this.form.submit();">
		<option value=""{if $periodo == ''} selected{/if}>Todos os períodos</option>
		{section name=p loop=$periodos}
		<option value="{$periodos[p]}"{if $periodos[p] == $periodo} selected{/if}>{$periodos[p]}</option>
		{/section}
	</select>
	{/if}

	<label for="muralestagio_id">Oferta:</label>
	<select name="muralestagio_id" id="muralestagio_id" onChange="this.form.submit();">
		<option value="0"{if $muralestagio_id == 0} selected{/if}>Todas as ofertas</option>
		{section name=o loop=$ofertas}
		<option value="{$ofertas[o].id}"{if $ofertas[o].id == $muralestagio_id} selected{/if}>{$ofertas[o].instituicao}{if $periodo == ''} ({$ofertas[o].periodo}){/if}</option>
		{/section}
	</select>

	<label for="grupo">Grupo:</label>
	<select name="grupo" id="grupo" onChange="this.form.submit();">
		<option value="todos"{if $grupo == 'todos'} selected{/if}>Todos os alunos</option>
		<option value="novos"{if $grupo == 'novos'} selected{/if}>Alunos novos</option>
		<option value="conhecidos"{if $grupo == 'conhecidos'} selected{/if}>Alunos que já estagiaram</option>
	</select>

	<noscript><input type="submit" value="Filtrar"></noscript>
</form>

<p><a href="ver-mural.php">Voltar para mural</a></p>

{* A impressão em PDF só existe para uma oferta do período atual. *}
{if $muralestagio_id > 0 && $periodo == $periodo_atual}
	<p><a href="imprime_inscricoes.php?muralestagio_id={$muralestagio_id}">Imprimir</a></p>
{/if}

<div style="overflow-x:auto;">
<table id="inscritos" class="display" border="1">
	<thead>
		<tr>
			<th>#</th>
			<th>TC</th>
			<th>DRE</th>
			<th>Nome</th>
			<th>Nível</th>
			<th>Ingresso</th>
			<th>Turno</th>
			<th>Período(s)</th>
			<th>Ofertas</th>
			<th>Situação</th>
			<th>Último estágio</th>
			<th>Início</th>
			<th>Última data</th>
			<th>Qtd</th>
			{if $sistema_autentica == 1}
			<th>E-mail</th>
			<th>Telefone</th>
			<th>Celular</th>
			{if $is_admin}
			<th class="no-sort">Excluir</th>
			{/if}
			{/if}
		</tr>
	</thead>

	<tbody>

	{* Uma linha por aluno; cor de fundo pela situação (novo x já estagiou),
	   como nas listas antigas. *}
	{section name=i loop=$alunos}
		<tr style="background-color:{if $alunos[i].situacao == 'novo'}#f6ecec{else}#add8e6{/if}">
			<td style="text-align:right">{$smarty.section.i.index+1}</td>
			<td style="text-align:center">{$alunos[i].tc}</td>
			<td style="text-align:right">{$alunos[i].registro}</td>
			<td>
				{if $sistema_autentica == 1}
				<a href="../alunos/ver_cada.php?registro={$alunos[i].registro}">{$alunos[i].nome}</a>
				{else}
				{$alunos[i].nome}
				{/if}
			</td>
			<td style="text-align:center">{$alunos[i].nivel}</td>
			<td style="text-align:center">{$alunos[i].data_ingresso}</td>
			<td style="text-align:center">{$alunos[i].turno}</td>
			<td style="text-align:center">{$alunos[i].periodos_texto}</td>
			<td>{$alunos[i].ofertas_texto}</td>
			<td style="text-align:center">{$alunos[i].situacao_texto}</td>
			<td data-order="{$alunos[i].estagio_periodo}">{$alunos[i].estagio_instituicao}{if $alunos[i].estagio_periodo != ''} ({$alunos[i].estagio_periodo}){/if}</td>
			<td style="text-align:center" data-order="{$alunos[i].data_inicio}">{$alunos[i].data_inicio_txt}</td>
			<td style="text-align:center" data-order="{$alunos[i].data_ultima}">{$alunos[i].data_ultima_txt}</td>
			<td style="text-align:center">{$alunos[i].quantidade}</td>

			{* Dados de contato e ações só para usuário autenticado. *}
			{if $sistema_autentica == 1}
			<td>{$alunos[i].email}</td>
			<td style="text-align:center">{$alunos[i].telefone}</td>
			<td style="text-align:center">{$alunos[i].celular}</td>

			{if $is_admin}
			<td style="text-align:center">
				{if $muralestagio_id > 0}
				{* Filtrado por oferta: exclui só a inscrição naquela oferta. *}
				<form name="excluirInscricao" id="excluirInscricao{$alunos[i].inscricao_id}" method="post"
					action="../inscricoes/excluir.php?muralestagio_id={$muralestagio_id}&periodo={$periodo}"
					onSubmit="return confirm('Excluir esta inscrição?');">
					<input type="hidden" name="id" value="{$alunos[i].inscricao_id}">
					<input type="submit" name="submit" value="Excluir">
				</form>
				{else}
				{* Sem filtro de oferta: tira o aluno do processo no período atual. *}
				<a href="mural-excluir_aluno.php?aluno_id={$alunos[i].aluno_id}&registro={$alunos[i].registro}"
					onclick="return confirm('Confirma a remoção das inscrições deste aluno no período atual?');">Excluir</a>
				{/if}
			</td>
			{/if}
			{/if}
		</tr>
	{/section}

	</tbody>
</table>
</div>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	$('#inscritos').DataTable({
		language: { url: '../libjs/datatables/pt-BR.json' },
		pageLength: 25,
		lengthMenu: [10, 25, 50, 100],
		order: [],           // mantém a ordem do servidor (nome do aluno)
		columnDefs: [
			{ targets: 'no-sort', orderable: false, searchable: false }
		]
	});
});
</script>
{/literal}

</body>

</html>
