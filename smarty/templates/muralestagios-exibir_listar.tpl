<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Mural de ofertas de estágio por período</title>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	periodo=document.getElementById('periodo').value;
	// alert(periodo);
	window.location="listar.php?periodo=" + periodo;
	return false;
}
</script>
{/literal}

</head>

<body>

<a href="javascript:history.back();">Voltar</a><br>

{if $is_admin}
<select name='periodo' id='periodo' onChange="return carrega_tabela();">
<option value='{$periodo}'>Período: {$periodo}</option>
<option value=''>Todos</option>
{section name=i loop=$periodos}
<option value='{$periodos[i]}' {if $periodos[i] == $periodo}selected{/if}>{$periodos[i]}</option>
{/section}
</select>
{/if}

<p>Ofertas: {$total_ofertas}, vagas: {$total_vagas}</p>

<table id="mural" class="display">
<caption>Mural de ofertas de estágio - {$periodo}</caption>
<thead>
<tr>
<th>Instituição</th>
<th>Período</th>
<th>Vagas</th>
<th>Inscritos</th>
<th>Benefícios</th>
<th>Horário</th>
<th>Carga hor.</th>
<th>Final de semana</th>
<th>Seleção</th>
<th>Encerramento</th>
<th>Forma</th>
<th>Contato</th>
</tr>
</thead>
<tbody>

{section name=item loop=$ofertas}
<tr>
<td><a href="../ver_cada.php?instituicao_id={$ofertas[item].instituicao_id}">{$ofertas[item].instituicao}</a></td>
<td class="coluna_centralizada">{$ofertas[item].periodo}</td>
<td class="coluna_centralizada">{$ofertas[item].vagas}</td>
<td class="coluna_centralizada"><a href="../listaInscritos.php?muralestagio_id={$ofertas[item].mural_estagio_id}">{$ofertas[item].quantidade_alunos}</a></td>
<td>{$ofertas[item].beneficios}</td>
<td class="coluna_centralizada">{$ofertas[item].horario}</td>
<td class="coluna_centralizada">{$ofertas[item].carga_horaria}</td>
<td class="coluna_centralizada">{$ofertas[item].final_de_semana}</td>
<td class="coluna_centralizada" data-order="{$ofertas[item].data_selecao_iso}">
{if $ofertas[item].data_selecao == ''}
Sem data
{else}
{$ofertas[item].data_selecao} Horário: {$ofertas[item].horario_selecao}
{/if}
</td>
<td class="coluna_centralizada" data-order="{$ofertas[item].data_inscricao_iso}">
{if $ofertas[item].data_inscricao == ''}
Sem data
{else}
{$ofertas[item].data_inscricao}
{/if}
</td>
<td class="coluna_centralizada">{$ofertas[item].forma_selecao}</td>
<td>{$ofertas[item].contato}</td>
</tr>
{/section}

</tbody>
</table>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#mural').DataTable({
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