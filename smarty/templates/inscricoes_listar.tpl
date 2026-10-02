<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>{if $titulo_oferta}Inscritos - {$titulo_oferta}{else}Inscrições por período{/if}</title>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	periodo=document.getElementById('periodo').value;
	window.location="listar.php?periodo=" + periodo;
	return false;
}
</script>
{/literal}

</head>
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
<a href="../atualizar/modifica.php?id={$inscritos[i].id}">Editar</a> |
<a href="../cancelar/cancela.php?id={$inscritos[i].id}&periodo={$inscritos[i].periodo}&muralestagio_id={$inscritos[i].muralestagio_id}" onclick="return confirm('Excluir esta inscrição?');">Excluir</a>
</td>
</tr>
{/section}

</tbody>
</table>

<div align="center">
<p><a href="../inserir/formulario.php?periodo={$periodo}">Nova inscrição</a></p>
</div>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#inscricoes').DataTable({
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