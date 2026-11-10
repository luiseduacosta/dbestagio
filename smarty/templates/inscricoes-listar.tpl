<<<<<<< HEAD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../estagio.css" rel="stylesheet" type="text/css">
<title>{if $titulo_oferta}Inscritos - {$titulo_oferta}{else}Inscrições por período{/if}</title>
=======
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{if $titulo_oferta}Inscritos - {$titulo_oferta}{else}Inscrições por período{/if}</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa

{literal}
<script type="text/javascript">
function carrega_tabela() {
<<<<<<< HEAD
	periodo=document.getElementById('periodo').value;
	window.location="../inscricoes/listar.php?periodo=" + periodo;
=======
	var periodo = document.getElementById('periodo').value;
	window.location = "listar.php?periodo=" + encodeURIComponent(periodo);
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa
	return false;
}
</script>
{/literal}

</head>
<<<<<<< HEAD
<body>

<a href="javascript:history.back();">Voltar</a><br>

<select name='periodo' id='periodo' onChange="return carrega_tabela();">
<option value='{$periodo}'>Período: {$periodo}</option>
{section name=i loop=$periodos}
<option value='{$periodos[i]}' {if $periodos[i] == $periodo}selected{/if}>{$periodos[i]}</option>
{/section}
</select>

{if $titulo_oferta}
<h4 style="text-align:center">Inscritos em: {$titulo_oferta} ({$periodo})</h4>
{/if}

<table id="inscricoes" class="display">
<thead>
<tr>
<th>Registro</th>
<th>Aluno</th>
<th>Instituição (oferta)</th>
<th>Período</th>
<th>Data inscrição</th>
<th>Ações</th>
</tr>
</thead>
<tbody>

{section name=i loop=$inscritos}
<tr>
<td class="coluna_direita">{$inscritos[i].registro}</td>
<td>
{if $smarty.cookies.usuario}
<a href="ver_cada.php?id={$inscritos[i].id}">{$inscritos[i].aluno_nome}</a>
{else}
{$inscritos[i].aluno_nome}
{/if}
{if $inscritos[i].aluno_email}<br><span style="font-size:80%">{$inscritos[i].aluno_email}</span>{/if}
</td>
<td>{$inscritos[i].oferta_instituicao}</td>
<td class="coluna_centralizada">{$inscritos[i].periodo}</td>
<td class="coluna_centralizada" data-order="{$inscritos[i].data_iso}">{$inscritos[i].data}</td>
<td>
<a href="ver_cada.php?id={$inscritos[i].id}">Ver</a> |
<a href="atualizar.php?id={$inscritos[i].id}">Editar</a> |
<a href="excluir.php?id={$inscritos[i].id}&periodo={$inscritos[i].periodo}&muralestagio_id={$inscritos[i].muralestagio_id}" onclick="return confirm('Excluir esta inscrição?');">Excluir</a>
</td>
</tr>
{/section}

</tbody>
</table>

<div align="center">
<p><a href="inserir.php?periodo={$periodo}">Nova inscrição</a></p>
</div>
=======
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · ESS/UFRJ</p>
			<h1>Inscrições<span>{if $titulo_oferta}Inscritos em: {$titulo_oferta} ({$periodo}){else}Período {$periodo}{/if}</span></h1>
		</div>

		<form class="periodo-form" name="seleciona_periodo">
			<label for="periodo">Período exibido</label>
			<select name="periodo" id="periodo" onChange="return carrega_tabela();">
				<option value="{$periodo}">Período: {$periodo}</option>
				{section name=i loop=$periodos}
				<option value="{$periodos[i]}"{if $periodos[i] == $periodo} selected{/if}>{$periodos[i]}</option>
				{/section}
			</select>
			<noscript><button type="submit">Ver</button></noscript>
		</form>
	</header>

	<section class="table-shell is-scroll" aria-label="Inscrições">
		<table id="inscricoes" class="display dt-table">
		<caption class="sr-only">Inscrições {if $titulo_oferta}na oferta {$titulo_oferta} {/if}no período {$periodo}.</caption>

		<thead>
		<tr>
			<th scope="col" class="coluna_direita">Registro</th>
			<th scope="col">Aluno</th>
			<th scope="col">Instituição (oferta)</th>
			<th scope="col" class="coluna_centralizada">Período</th>
			<th scope="col" class="coluna_centralizada">Data inscrição</th>
			<th scope="col" data-orderable="false">Ações</th>
		</tr>
		</thead>

		<tbody>
		{section name=i loop=$inscritos}
			<tr>
			<td class="coluna_direita">{$inscritos[i].registro}</td>
			<td>
				{if $smarty.cookies.usuario}
				<a href="ver_cada.php?id={$inscritos[i].id}">{$inscritos[i].aluno_nome}</a>
				{else}
				{$inscritos[i].aluno_nome}
				{/if}
				{if $inscritos[i].aluno_email}<span class="form-hint">{$inscritos[i].aluno_email}</span>{/if}
			</td>
			<td>{$inscritos[i].oferta_instituicao}</td>
			<td class="coluna_centralizada">{$inscritos[i].periodo}</td>
			<td class="coluna_centralizada" data-order="{$inscritos[i].data_iso}">{$inscritos[i].data}</td>
			<td class="action-links">
				<a href="ver_cada.php?id={$inscritos[i].id}">Ver</a>
				<a href="atualizar.php?id={$inscritos[i].id}">Editar</a>
				<a class="is-danger" href="excluir.php?id={$inscritos[i].id}&periodo={$inscritos[i].periodo}&muralestagio_id={$inscritos[i].muralestagio_id}" onclick="return confirm('Excluir esta inscrição?');">Excluir</a>
			</td>
			</tr>
		{sectionelse}
			<tr>
			<td colspan="6" class="empty-state">Nenhuma inscrição encontrada para este período.</td>
			</tr>
		{/section}
		</tbody>
		</table>
	</section>

	<div class="action-row">
		<a class="btn" href="inserir.php?periodo={$periodo}">Nova inscrição</a>
	</div>

</main>
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#inscricoes').DataTable({
		language: { url: '../libjs/datatables/pt-BR.json' },
		pageLength: 25,
		lengthMenu: [10, 25, 50, 100],
		order: []
	});
});
</script>
{/literal}

</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa
