<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Lista de instituições</title>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	turma=document.getElementById('turma').value;
	ordem=document.getElementById('ordem').value;
	// alert(turma);
	window.location="listar.php?turma=" + turma;
	return false;
}
</script>
{/literal}

</head>

<body>

<input type=hidden name='ordem' id='ordem' value='{$ordem}'>

<select name='turma' id='turma' onChange="return carrega_tabela();">
<option value='0'>Selecione período</option>
<option value='0'>Todos</option>
{section name=i loop=$periodos}
<option value='{$periodos[i]}'>{$periodos[i]}</option>
{/section}
</select>

<div align="center">
<table border="1">
<caption>Tabela de instituições {$turma}</caption>

<thead>
<tr>
<th>Id</th>
<th><a href="?turma={$turma}&ordem=convenio">Convênio</a></th>
<th><a href="?turma={$turma}&ordem=instituicao">Instituições</a></th>
<th><a href="?turma={$turma}&ordem=beneficio">Bene- <br>fícios</a></th>
<th><a href="?turma={$turma}&ordem=turma">Turma</a></th>
<th><a href="?turma={$turma}&ordem=alunos">Alunos</a></th>
<th><a href="?turma={$turma}&ordem=periodos">Períodos</a></th>
<th><a href="?turma={$turma}&ordem=q_supervi">Super- <br>visores</a></th>
<th><a href="?turma={$turma}&ordem=area">Áreas</a></th>
</tr>
</thead>

<tbody>

{assign var="i" value=1}
{section name=elementos loop=$instituicoes}
<tr>
<td style="text-align:right">{$i++}</td>

{* Convenio *}
<td style="text-align:right"><a href="http://www.pr1.ufrj.br/estagios/info.php?codEmpresa={$instituicoes[elementos].convenio}">{$instituicoes[elementos].convenio}</a></td>

{* Instituicoes *}
<td><a href="../exibir/ver_cada.php?id_instituicao={$instituicoes[elementos].id_instituicao}">{$instituicoes[elementos].instituicao}</a></td>

{* Beneficios *}
<td>{$instituicoes[elementos].beneficio}</td>

{* Turma *}
{if $turma}
<td style="text-align:center"><a href="../../alunos/exibir/alunos_instituicao.php?id_instituicao={$instituicoes[elementos].id_instituicao}&periodo={$turma}">{$turma}</a></td>
{else}
<td style="text-align:center"><a href="../../alunos/exibir/alunos_instituicao.php?id_instituicao={$instituicoes[elementos].id_instituicao}&periodo={$instituicoes[elementos].turma}">{$instituicoes[elementos].turma}</a></td>
{/if}

{* Alunos *}
{if $turma}
	<td style="text-align:center"><a href="../../alunos/exibir/alunos_instituicao.php?id_instituicao={$instituicoes[elementos].id_instituicao}&periodo={$turma}">{$instituicoes[elementos].alunos}</a></td>
{else}
	{if $instituicoes[elementos].alunos == 0}
	<td style="text-align:center">{$instituicoes[elementos].alunos}</td>
	{else}
	<td style="text-align:center"><a href="../../alunos/exibir/alunos_instituicao.php?id_instituicao={$instituicoes[elementos].id_instituicao}">{$instituicoes[elementos].alunos}</a></td>
	{/if}
{/if}

{* Períodos *}
<td style="text-align:center">{$instituicoes[elementos].periodos}</td>

{* Supervisores *}
{if $instituicoes[elementos].supervisores == 0}
	<td  style="text-align:center">{$instituicoes[elementos].supervisores}</td>
{else}
	<td  style="text-align:center">
	<a href="../../assistentes/exibir/listar_todos.php?id_instituicao={$instituicoes[elementos].id_instituicao}">{$instituicoes[elementos].supervisores}</a>
	</td>
{/if}

{* Area *}
<td>{$instituicoes[elementos].area}</td>
</tr>
{/section}

</tbody>
</table>
</div>

</body>

</html>
