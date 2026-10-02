<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Professores</title>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	busca=document.getElementById('busca').value;
	status=document.getElementById('status').value;
	window.location="listar.php?busca=" + encodeURIComponent(busca) + "&status=" + status;
	return false;
}
</script>
{/literal}

</head>
<body>

<a href="javascript:history.back();">Voltar</a><br>

<form name="filtros" onsubmit="return carrega_tabela();">
Busca: <input type="text" id="busca" name="busca" value="{$busca}" onkeyup="if (event.keyCode == 13) return carrega_tabela();">
Status:
<select id="status" name="status" onChange="return carrega_tabela();">
{foreach item=s key=k from=$statuses}
<option value="{$k}" {if $k == $status}selected{/if}>{$s}</option>
{/foreach}
</select>
<input type="submit" value="Filtrar">
</form>

<table id="professores" class="display">
<thead>
<tr>
<th>Nome</th>
<th>Departamento</th>
<th>E-mail</th>
<th>Telefone</th>
<th>Celular</th>
<th>Status</th>
<th data-orderable="false">Nº estágios</th>
<th data-orderable="false">Ações</th>
</tr>
</thead>
<tbody>

{foreach item=p from=$professores}
<tr>
<td>
{if $smarty.cookies.usuario}
<a href="ver_cada.php?professor_id={$p.id}">{$p.nome}</a>
{else}
{$p.nome}
{/if}
</td>
<td>{$p.departamento}</td>
<td>{$p.email}</td>
<td class="coluna_centralizada">{$p.telefone}</td>
<td class="coluna_centralizada">{$p.celular}</td>
<td>{$p.status}</td>
<td class="coluna_centralizada">{$p.num_estagios}</td>
<td>
{if $smarty.cookies.usuario}
<a href="ver_cada.php?professor_id={$p.id}">Ver</a> |
<a href="../atualizar/atualiza.php?professor_id={$p.id}">Editar</a> |
<a href="../cancelar/cancela.php?professor_id={$p.id}" onclick="return confirm('Excluir este professor?');">Excluir</a>
{/if}
</td>
</tr>
{/foreach}

</tbody>
</table>

<div align="center">
<p><a href="../inserir/inserir.php">Novo professor</a></p>
</div>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#professores').DataTable({
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