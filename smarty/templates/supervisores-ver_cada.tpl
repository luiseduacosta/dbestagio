<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Supervisores - {$nome}</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

{literal}
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

	{* Barra de navegação: exclusiva do administrador. *}
	{if $isAdmin}
	<table id="navegacao" class="toolbar">
	<tbody>
	<tr>

	<td>
	<form action="ver_cada.php" method="POST">
	<input type="hidden" name="indice" value="0">
	<input type="hidden" name="periodo" value="{$periodo}">
	<input type="submit" value="Primeiro">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="POST">
	<input type="hidden" name="indice" value="{$indice-10}">
	<input type="hidden" name="periodo" value="{$periodo}">
	<input type="submit" value="- 10">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="POST">
	<input type="hidden" name="indice" value="{$indice-1}">
	<input type="hidden" name="periodo" value="{$periodo}">
	<input type="submit" value="Retrocede">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="POST">
	<input type="hidden" name="indice" value="{$indice+1}">
	<input type="hidden" name="periodo" value="{$periodo}">
	<input type="submit" value="Avança">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="POST">
	<input type="hidden" name="indice" value="{$indice+10}">
	<input type="hidden" name="periodo" value="{$periodo}">
	<input type="submit" value="+ 10">
	</form>
	</td>

	<td>
	<form action="ver_cada.php" method="POST">
	<input type="hidden" name="indice" value="{$ultimo}">
	<input type="hidden" name="periodo" value="{$periodo}">
	<input type="submit" value="Último">
	</form>
	</td>

	<td>
	<form action="inserir.php" method="GET">
	<input type="submit" value="Inserir">
	</form>
	</td>

	<td>
	<form action="atualizar.php" method="GET">
	<input type="hidden" name="supervisor_id" value="{$supervisor_id}">
	<input type="submit" value="Modificar">
	</form>
	</td>

	<td class="is-danger">
	<form action="excluir.php" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este supervisor?');">
	<input type="hidden" name="supervisor_id" value="{$supervisor_id}">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" value="Excluir">
	</form>
	</td>

	</tr>
	</tbody>
	</table>
	{/if}

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Supervisores</p>
			<h1>Supervisor: {$nome}<span>{$cress}{if $cress} · {/if}{$escola}</span></h1>
		</div>

		{if $isAdmin}
		<form class="periodo-form">
			<label for="periodo">Período</label>
			<select name="periodo" id="periodo" size="1" onChange="get_periodo();">
			{if !$periodo}
				<option value="0">Seleciona período (Todos)</option>
			{else}
				<option value="0">Período: {$periodo}</option>
			{/if}
			{foreach from=$periodos item=p}
			<option value="{$p}">{$p}</option>
			{/foreach}
			</select>
		</form>
		{/if}
	</header>

	<table class="ficha card-table">
	<tbody>
	<tr><th>Id</th><td>{$supervisor_id}</td></tr>
	<tr><th>Cress</th><td>{$cress}</td></tr>
	<tr><th>Nome</th><td>{$nome}</td></tr>
	<tr><th>CPF</th><td>{$cpf}</td></tr>
	<tr><th>Telefone</th><td>{if $telefone}({$codigo_telefone}) {$telefone}{else}—{/if}</td></tr>
	<tr><th>Celular</th><td>{if $celular}({$codigo_celular}) {$celular}{else}—{/if}</td></tr>
	<tr><th>E-mail</th><td>{if $email}<a href="mailto:{$email}">{$email}</a>{else}—{/if}</td></tr>
	<tr><th>Endereço</th><td>{$endereco}</td></tr>
	<tr><th>Bairro</th><td>{$bairro}</td></tr>
	<tr><th>Município</th><td>{$municipio}</td></tr>
	<tr><th>CEP</th><td>{$cep}</td></tr>
	<tr><th>Região (CRESS)</th><td>{$regiao}</td></tr>
	<tr><th>Escola</th><td>{$escola}</td></tr>
	<tr><th>Ano de formação</th><td>{$ano_formacao}</td></tr>
	<tr><th>Cargo</th><td>{$cargo}</td></tr>

	<tr>
	<th>Curso</th>
	<td>
	{if $id_curso}
	    <a href="../curso/ver_cada_supervisor.php?id_supervisor={$id_curso}">{$id_curso}</a>
	    {* atualiza_supervisor_estagio.php exige sessao admin; esconder o link evita
	       que aluno, professor e supervisor recebam "Acesso restrito" ao clicar. *}
	    {if $isAdmin}&nbsp;<a class="btn btn-secondary btn-sm" href="../curso/atualiza_supervisor_estagio.php?id_supervisor_curso={$id_curso}&indice={$indice}">Sincronizar</a>{/if}
	{else}
	    —
	{/if}
	</td>
	</tr>

	{foreach from=$emprego item=emp}
	<tr>
		<th>Instituição vinculada</th>
		<td>
			<a href="../instituicoes/ver_cada.php?instituicao_id={$emp.id}">{$emp.instituicao}</a>
			{if $isAdmin}
			&nbsp;<a class="btn btn-danger btn-sm" href="inst_supervisor.php?supervisor_id={$supervisor_id}&instituicao_id={$emp.id}" onclick="return confirm('Desvincular esta instituição?');">Desvincular</a>
			{/if}
		</td>
	</tr>
	{/foreach}

	<tr><th>Observações</th><td>{$observacoes}</td></tr>
	</tbody>
	</table>

	{* Vincular instituição: exclusivo do administrador. *}
	{if $isAdmin}
	<section class="table-shell" aria-label="Vincular instituição">
		<form name="sel_instituicao" action="ver_cada.php" method="POST" class="periodo-form">
			<label for="num_instituicao">Vincular instituição</label>
			<select name="num_instituicao" id="num_instituicao">
				<option value="0">Selecione uma instituição para vincular</option>
				{foreach from=$instituicoes item=inst}
				<option value="{$inst.id}">{$inst.instituicao|truncate:50}</option>
				{/foreach}
			</select>
			<input type="hidden" name="supervisor_id" value="{$supervisor_id}">
			<input type="hidden" name="indice" value="{$indice}">
			<button type="submit" class="btn btn-sm">Acrescentar instituição</button>
		</form>
	</section>
	{/if}

	{if $alunos}
	<section class="table-shell" aria-label="Alunos supervisionados">
		<p class="eyebrow">Alunos supervisionados</p>
		<table id="estagiarios" class="display dt-table">
		<thead>
		<tr>
		<th>Registro</th>
		<th>Nome</th>
		<th>Instituição</th>
		<th>Professor</th>
		<th>Período</th>
		<th>Nota</th>
		<th>CH</th>
		</tr>
		</thead>
		<tbody>
		{foreach from=$alunos item=al}
		<tr>
		<td class="coluna_direita">{$al.registro}</td>
		<td><a href="../alunos/ver_cada.php?aluno_id={$al.aluno_id}">{$al.nome}</a></td>
		<td><a href="../instituicoes/ver_cada.php?instituicao_id={$al.instituicao_id}">{$al.instituicao}</a></td>
		<td>
		{if $al.professor_id && $al.professor}
		<a href="../professores/ver_cada.php?professor_id={$al.professor_id}">{$al.professor}</a>
		{else}
		—
		{/if}
		</td>
		<td class="coluna_centralizada">{$al.periodo}</td>
		<td class="coluna_centralizada">{if $al.nota ne ''}{$al.nota}{else}—{/if}</td>
		<td class="coluna_centralizada">{if $al.ch ne ''}{$al.ch}{else}—{/if}</td>
		</tr>
		{/foreach}
		</tbody>
		</table>
	</section>
	{/if}

	<div class="action-row">
		{if $pode_editar}
		<a class="action-btn" href="atualizar.php?supervisor_id={$supervisor_id}">Editar dados do supervisor</a>
		{/if}
		<a class="btn btn-secondary" href="listar.php">Voltar para a listagem</a>
	</div>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	// A tabela so existe quando o supervisor tem estagiarios vinculados.
	if ($('#estagiarios').length) {
		$('#estagiarios').DataTable({
			language: { url: '../libjs/datatables/pt-BR.json' },
			pageLength: 25,
			lengthMenu: [10, 25, 50, 100],
			order: []
		});
	}
});
</script>
{/literal}

</body>
</html>
