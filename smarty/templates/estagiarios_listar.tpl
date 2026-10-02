<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Estagiários</title>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	periodo=document.getElementById('periodo').value;
	busca=document.getElementById('busca').value;
	window.location="listar.php?periodo=" + periodo + "&busca=" + encodeURIComponent(busca);
	return false;
}
</script>
{/literal}

</head>
<body>

<a href="javascript:history.back();">Voltar</a><br>

<form name="filtros" onsubmit="return carrega_tabela();">
Período:
<select id="periodo" name="periodo" onChange="return carrega_tabela();">
<option value="">Todos</option>
{foreach item=p from=$periodos}
<option value="{$p}" {if $p == $periodo}selected{/if}>{$p}</option>
{/foreach}
</select>
Busca: <input type="text" id="busca" name="busca" value="{$busca}" onkeyup="if (event.keyCode == 13) return carrega_tabela();">
<input type="submit" value="Filtrar">
</form>

<table id="estagiarios" class="display">
<thead>
<tr>
<th>Período</th>
<th>Registro</th>
<th>Aluno</th>
<th>Instituição</th>
<th>Supervisor</th>
<th>Professor</th>
<th>Nível</th>
<th>Nota</th>
<th data-orderable="false">Ações</th>
</tr>
</thead>
<tbody>

{foreach item=e from=$estagiarios}
<tr>
<td class="coluna_centralizada">{$e.periodo}</td>
<td class="coluna_centralizada">{$e.registro}</td>
<td>
{if $smarty.cookies.usuario}
<a href="ver_cada.php?id={$e.id}">{$e.aluno_nome}</a>
{else}
{$e.aluno_nome}
{/if}
</td>
<td>{$e.instituicao}</td>
<td>{$e.supervisor}</td>
<td>{$e.professor}</td>
<td class="coluna_centralizada">{$e.nivel}</td>
<td class="coluna_centralizada">{$e.nota}</td>
<td>
{if $smarty.cookies.usuario}
<a href="ver_cada.php?id={$e.id}">Ver</a> |
<a href="../atualizar/atualiza.php?id={$e.id}">Editar</a> |
<a href="../cancelar/cancela.php?id={$e.id}" onclick="return confirm('Excluir este estágio?');">Excluir</a>
{/if}
</td>
</tr>
{/foreach}

</tbody>
</table>

<div align="center">
<p><a href="../inserir/inserir.php?periodo={$periodo}">Novo estágio</a></p>
</div>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#estagiarios').DataTable({
		language: { url: '../../libjs/datatables/pt-BR.json' },
		pageLength: 25,
		lengthMenu: [10, 25, 50, 100],
		order: []
	});
});
</script>
{/literal}

</body>
</html>