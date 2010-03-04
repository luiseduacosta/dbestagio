<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Lista de instituiï¿½ï¿½es</title>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	turma=document.getElementById('turma').value;
	natureza=document.getElementById('natureza').value;
	ordem=document.getElementById('ordem').value;
	instituicao=document.getElementById('instituicao').value;
	// alert(turma);
	window.location="listar.php?turma=" + turma + "&ordem=" + ordem + "&instituicao=" + instituicao + "&natureza=" + natureza;
	return false;
}
</script>
{/literal}

</head>

<body>

<input type=hidden name='ordem' id='ordem' value='{$ordem}'>
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

<p>Professores: <a href="../../professores/exibir/listar.php?periodo={$turma}">{$total_professores}</a>, instituiões: {$total_instituicoes}, supervisores: {$total_supervisores}, alunos: {$total_alunos}, períodos: {$total_periodos}</p>

<div align="center">
<table border="1">
<caption>Tabela de instituições {$turma}</caption>

<thead>
<tr>
<th>Id</th>
<th><a href="?instituicao={$instituicao}&turma={$turma}&ordem=convenio">Convênio</a></th>
<th><a href="?instituicao={$instituicao}&turma={$turma}&ordem=instituicao">Instituições</a></th>
<th><a href="?instituicao={$instituicao}&turma={$turma}&ordem=beneficio">Bene- <br>fí­cios</a></th>
<th><a href="?instituicao={$instituicao}&turma={$turma}&ordem=turma">Turma</a></th>
<th><a href="?instituicao={$instituicao}&turma={$turma}&ordem=alunos">Alunos</a></th>
<th><a href="?instituicao={$instituicao}&turma={$turma}&ordem=periodos">Perí­odos</a></th>
<th><a href="?instituicao={$instituicao}&turma={$turma}&ordem=q_supervi">Super- <br>visores</a></th>
<th><a href="?instituicao={$instituicao}&turma={$turma}&ordem=area">Áeas</a></th>
<th><a href="?instituicao={$instituicao}&turma={$turma}&ordem=natureza">Natureza</a></th>
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
<td><a href="../exibir/ver_cada.php?id_instituicao={$instituicoes[elementos].id_instituicao}">{$instituicoes[elementos].instituicao}</a></td>

{* Beneficios *}
<td>{$instituicoes[elementos].beneficio}</td>

{* Turma *}
{if $turma}
	<td style="text-align:center"><a href="../../alunos/exibir/listar.php?seleciona_instituicao={$instituicoes[elementos].id_instituicao}&seleciona_periodo={$turma}">{$turma}</a></td>
{else}
	<td style="text-align:center"><a href="../../alunos/exibir/listar.php?seleciona_instituicao={$instituicoes[elementos].id_instituicao}&seleciona_periodo={$instituicoes[elementos].turma}">{$instituicoes[elementos].turma}</a></td>
{/if}

{* Alunos *}
{if $turma}
	<td style="text-align:center"><a href="../../alunos/exibir/listar.php?seleciona_instituicao={$instituicoes[elementos].id_instituicao}&seleciona_periodo={$turma}">{$instituicoes[elementos].alunos}</a></td>
{else}
	{if $instituicoes[elementos].alunos == 0}
		<td style="text-align:center">{$instituicoes[elementos].alunos}</td>
	{else}
		<td style="text-align:center"><a href="../../alunos/exibir/listar.php?seleciona_instituicao={$instituicoes[elementos].id_instituicao}&seleciona_periodo={$instituicoes[elementos].turma}">{$instituicoes[elementos].alunos}</a></td>
	{/if}
{/if}

{* Perí­odos *}
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

</body>

</html>
