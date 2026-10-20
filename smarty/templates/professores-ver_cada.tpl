<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Professor - {$professor_nome}</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	{* Barra de navegação e ações de CRUD: exclusiva do admin. O professor
	   acessa o próprio cadastro pelo botão do rodapé. *}
	{if $isAdmin}
	<table id="navegacao" class="toolbar">
	<tbody>
	<tr>

	{if $primeiro_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="professor_id" value="{$primeiro_id}">
	<input type="submit" value="Primeiro">
	</form>
	</td>
	{/if}

	{if $menos_10_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="professor_id" value="{$menos_10_id}">
	<input type="submit" value="-10">
	</form>
	</td>
	{/if}

	{if $anterior_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="professor_id" value="{$anterior_id}">
	<input type="submit" value="Retroceder">
	</form>
	</td>
	{/if}

	{if $proximo_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="professor_id" value="{$proximo_id}">
	<input type="submit" value="Avançar">
	</form>
	</td>
	{/if}

	{if $mais_10_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="professor_id" value="{$mais_10_id}">
	<input type="submit" value="+10">
	</form>
	</td>
	{/if}

	{if $ultimo_id}
	<td>
	<form action="ver_cada.php" method="get">
	<input type="hidden" name="professor_id" value="{$ultimo_id}">
	<input type="submit" value="Último">
	</form>
	</td>
	{/if}

	<td>
	<form action="inserir.php" method="get">
	<input type="submit" value="Inserir">
	</form>
	</td>

	<td>
	<form action="atualizar.php" method="get">
	<input type="hidden" name="professor_id" value="{$professor_id}">
	<input type="submit" value="Modificar">
	</form>
	</td>

	<td class="is-danger">
	<form action="excluir.php" method="get" onClick="return confirm('Tem certeza?');">
	<input type="hidden" name="professor_id" value="{$professor_id}">
	<input type="submit" value="Excluir">
	</form>
	</td>

	</tr>
	</tbody>
	</table>
	{/if}

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Professores</p>
			<h1>Professor: {$professor_nome}<span>{$num_estagios} estágio(s) · {$num_instituicoes} instituição(ões)</span></h1>
		</div>
	</header>

	<table class="ficha card-table">
	<tbody>
	<tr><td>Id</td><td>{$professor_id}</td></tr>
	<tr><td>Nome</td><td>{$professor_nome}</td></tr>
	<tr><td>CPF</td><td>{$cpf}</td></tr>
	<tr><td>SIAPE</td><td>{$siape}</td></tr>
	<tr><td>Cress</td><td>{$cress}</td></tr>
	<tr><td>Região (CRESS)</td><td>{$regiao}</td></tr>
	<tr><td>Telefone</td><td>{$telefone}</td></tr>
	<tr><td>Celular</td><td>{$celular}</td></tr>
	<tr><td>E-mail</td><td>{$email}</td></tr>
	<tr><td>Currículo Lattes</td><td>{$curriculolattes}</td></tr>
	<tr><td>Atualização Lattes</td><td>{$atualizacaolattes}</td></tr>
	<tr><td>Data de ingresso</td><td>{$dataingresso}</td></tr>
	<tr><td>Tipo de cargo</td><td>{$tipocargo}</td></tr>
	<tr><td>Departamento</td><td>{$departamento}</td></tr>
	<tr><td>Data de egresso</td><td>{$dataegresso}</td></tr>
	<tr><td>Motivo de egresso</td><td>{$motivoegresso}</td></tr>
	<tr><td>Status</td><td>{$status}</td></tr>
	<tr><td>Observações</td><td>{$observacoes}</td></tr>
	<tr><td>Registrado em</td><td>{$created}</td></tr>
	<tr><td>Alterado em</td><td>{$modified}</td></tr>
	</tbody>
	</table>

	<section class="table-shell" aria-label="Estagiários do professor">
	<table id="estagiarios" class="display dt-table">
	<thead>
	<tr>
	<th><a href="?professor_id={$professor_id}&ordem=a.registro">Registro</a></th>
	<th><a href="?professor_id={$professor_id}&ordem=a.nome">Aluno</a></th>
	<th><a href="?professor_id={$professor_id}&ordem=e.periodo">Período</a></th>
	<th><a href="?professor_id={$professor_id}&ordem=e.nivel">Nível</a></th>
	<th><a href="?professor_id={$professor_id}&ordem=i.instituicao">Instituição</a></th>
	<th><a href="?professor_id={$professor_id}&ordem=s.nome">Supervisor</a></th>
	<th>Nota</th>
	<th>CH</th>
	{if $isAdmin || $pode_editar}<th data-orderable="false">Lançar</th>{/if}
	</tr>
	</thead>
	<tbody>

	{foreach item=e from=$estagiarios}
	<tr>
	<td class="coluna_direita">{$e.registro}</td>
	<td><a href="../alunos/ver_cada.php?aluno_id={$e.aluno_id}">{$e.aluno_nome}</a></td>
	<td class="coluna_centralizada">{$e.periodo}</td>
	<td class="coluna_centralizada">{$e.nivel}</td>
	<td>
	{if $e.instituicao_id}
	<a href="../instituicoes/ver_cada.php?instituicao_id={$e.instituicao_id}">{$e.instituicao}</a>
	{else}
	{$e.instituicao}
	{/if}
	</td>
	<td>
	{if $e.supervisor_id}
	<a href="../supervisores/ver_cada.php?supervisor_id={$e.supervisor_id}">{$e.supervisor}</a>
	{else}
	{$e.supervisor}
	{/if}
	</td>

	{* Nota e carga horária: lançadas pelo professor responsável ou por um
	   administrador. O formulário usa o atributo form= para levar os dois
	   campos (em células distintas) para o mesmo POST. *}
	{if $isAdmin || $pode_editar}
	<td class="coluna_centralizada">
	<form id="notach_{$e.estagiario_id}" action="../estagiarios/salvar_nota_ch.php" method="post">
	<input type="hidden" name="estagiario_id" value="{$e.estagiario_id}">
	</form>
	<input form="notach_{$e.estagiario_id}" type="text" name="nota" value="{$e.nota}" size="3" maxlength="5" class="nota-ch-input" aria-label="Nota de {$e.aluno_nome}">
	</td>
	<td class="coluna_centralizada">
	<input form="notach_{$e.estagiario_id}" type="text" name="ch" value="{$e.ch}" size="4" maxlength="5" class="nota-ch-input" aria-label="Carga horária de {$e.aluno_nome}">
	</td>
	<td class="coluna_centralizada">
	<button type="submit" form="notach_{$e.estagiario_id}" class="action-btn">Salvar</button>
	</td>
	{else}
	<td class="coluna_centralizada">{$e.nota}</td>
	<td class="coluna_centralizada">{$e.ch}</td>
	{/if}
	</tr>
	{/foreach}

	</tbody>
	</table>
	</section>

	<div class="action-row">
		{if $isAdmin || $pode_editar}
		<a class="action-btn" href="atualizar.php?professor_id={$professor_id}">Editar dados do professor</a>
		{/if}
		<a class="btn btn-secondary" href="listar.php">Voltar para a listagem</a>
		<a class="btn btn-secondary" href="javascript:history.back();">Voltar</a>
	</div>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#estagiarios').DataTable({
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
