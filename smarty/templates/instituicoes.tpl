<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Lista de instituições</title>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	turma=document.getElementById('turma').value;
	natureza=document.getElementById('natureza').value;
	instituicao=document.getElementById('instituicao').value;
	// alert(turma);
	window.location="listar.php?turma=" + turma + "&instituicao=" + instituicao + "&natureza=" + natureza;
	return false;
}
</script>
{/literal}

</head>

<body>

<input type=hidden name='instituicao' id='instituicao' value='{$instituicao}'>

<select name='turma' id='turma' onChange="return carrega_tabela();">
<option value='{$turma}'>Período: {$turma}</option>
<option value='0'>Todos</option>
{section name=i loop=$periodos}
<option value='{$periodos[i]}'>{$periodos[i]}</option>
{/section}
</select>

<select name='natureza' id='natureza' onChange="return carrega_tabela();">
<option value='{$natureza}'>Natureza: {$natureza}</option>
<option value='0'>Todos</option>
{section name=i loop=$naturezas}
<option value='{$naturezas[i]}'>{$naturezas[i]}</option>
{/section}
</select>

<p>Professores: <a href="../../professores/exibir/listar.php?periodo={$turma}">{$total_professores}</a>, instituições: {$total_instituicoes}, supervisores: {$total_supervisores}, alunos: {$total_alunos}, períodos: {$total_periodos}</p>

<div align="center">
<table id="instituicoes" class="display">
<caption>Tabela de instituições {$turma}</caption>

<thead>
<tr>
<th>Id</th>
<th>Convênio</th>
<th>Instituições</th>
<th>Seguro</th>
<th>Benefícios</th>
<th>Turma</th>
<th>Alunos</th>
<th>Períodos</th>
<th>Supervisores</th>
<th>Áreas</th>
<th>Natureza</th>
</tr>
</thead>

<tbody>

{assign var="i" value=1}
{section name=elementos loop=$instituicoes}
<tr>
<td style="text-align:right">{$i++}</td>

{* Convenio *}
{if $instituicoes[elementos].convenio != 0}
	<td style="text-align:right"><a href="http://www.pr1.ufrj.br/estagios/info.php?codEmpresa={$instituicoes[elementos].convenio}">{$instituicoes[elementos].convenio}</a></td>
{else}
	<td style="text-align:right">&nbsp;</td>
{/if}

{* Instituicoes *}
<td><a href="../exibir/ver_cada.php?instituicao_id={$instituicoes[elementos].instituicao_id}">{$instituicoes[elementos].instituicao}</a></td>

{* Seguro *}
{if $instituicoes[elementos].seguro eq 0}
	<td>Instituição</td>
{else}
	<td>UFRJ</td>
{/if}

{* Beneficios *}
<td>{$instituicoes[elementos].beneficio}</td>

{* Turma *}
{if $turma}
	<td style="text-align:center"><a href="../../alunos/exibir/listar.php?seleciona_instituicao={$instituicoes[elementos].instituicao_id}&seleciona_periodo={$turma}">{$turma}</a></td>
{else}
	<td style="text-align:center"><a href="../../alunos/exibir/listar.php?seleciona_instituicao={$instituicoes[elementos].instituicao_id}&seleciona_periodo={$instituicoes[elementos].turma}">{$instituicoes[elementos].turma}</a></td>
{/if}

{* Alunos *}
{if $turma}
	<td style="text-align:center"><a href="../../alunos/exibir/listar.php?seleciona_instituicao={$instituicoes[elementos].instituicao_id}&seleciona_periodo={$turma}">{$instituicoes[elementos].alunos}</a></td>
{else}
	{if $instituicoes[elementos].alunos == 0}
		<td style="text-align:center">{$instituicoes[elementos].alunos}</td>
	{else}
		<td style="text-align:center"><a href="../../alunos/exibir/listar.php?seleciona_instituicao={$instituicoes[elementos].instituicao_id}&seleciona_periodo={$instituicoes[elementos].turma}">{$instituicoes[elementos].alunos}</a></td>
	{/if}
{/if}

{* Periodos *}
<td style="text-align:center">{$instituicoes[elementos].periodos}</td>

{* Supervisores *}
{if $instituicoes[elementos].supervisores == 0}
	<td  style="text-align:center">{$instituicoes[elementos].supervisores}</td>
{else}
	<td  style="text-align:center">
	<a href="../../supervisores/exibir/listar_todos.php?instituicao_id={$instituicoes[elementos].instituicao_id}">{$instituicoes[elementos].supervisores}</a>
	</td>
{/if}

{* Area *}
<td>{$instituicoes[elementos].area}</td>

{* Natureza *}
<td>{$instituicoes[elementos].natureza}</td>

<!--
{* Url *}
{if $instituicoes[elementos].url}
	<td><a href='{$instituicoes[elementos].url}'></a>{$instituicoes[elementos].url}</td>
{else}
	<td>&nbsp;</td>
{/if}
//-->

</tr>
{/section}

</tbody>
</table>
</div>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#instituicoes').DataTable({
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
