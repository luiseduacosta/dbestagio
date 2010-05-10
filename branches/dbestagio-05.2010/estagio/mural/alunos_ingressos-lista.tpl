<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Lista de alunos</title>
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="Luis Acosta" content="author">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
<style type="text/css">
@import url("../estagio.css");
</style>
{literal}
<script language="JavaScript" type="text/javascript">
function carrega_tabela() {
	turma=document.getElementById('turma').value;
	turno=document.getElementById('turno').value;
	ordem=document.getElementById('ordem').value;
	periodo_atual=document.getElementById('periodo_atual').value;
	/* alert(turma); */
	window.location="alunos_ingressos-lista.php?turma=" + turma +"&turno=" + turno + "&ordem=" + ordem + "&periodo_atual=" + periodo_atual;
	return false;
}
</script>
{/literal}
</head>

<body style="direction: ltr;">

<form action='#' method='POST'>
<select name='turma' id='turma' onChange="return carrega_tabela();">
{if $turma}
<option value='{$turma}'>{$turma}</option>
{else}
<option value='0'>Seleciona período</option>
{/if}
{section name=i loop=$periodos}
<option value='{$periodos[i].periodo}'>{$periodos[i].periodo}</option>
{/section}
</select>

<select name='turno' id='turno' onChange="return carrega_tabela();">
{if $turno}
<option value='{$turno}'>{$turno}</option>
{else}
<option value='0'>Seleciona turno</option>
{/if}
<option value=''>Ambos</option>
<option value='D'>Diurno</option>
<option value='N'>Noturno</option>
</select>

<input type='hidden' name='ordem' id='ordem' value='{$ordem}'>

</form>

<form action='#' method='POST'>
<label for='periodo_atual'>Período atual: </label>
<input type='text' name='periodo_atual' id='periodo_atual' value='{$periodo_atual}' maxlength='6' size='6'>
<input type='submit' value='Confirma'>
</form>

<p style="text-indent:0%; line-height:1px;">{$tempo_cursado}º período</p>

{if $turno == "D" || $turno == "N"}
<h1>Alunos que ingressaram na ESS em {$turma} turno {$turno}</h1>
{else}
<h1>Alunos que ingressaram na ESS em {$turma}</h1>
{/if}
<div align="center">
<table border='1'>
<tr>
<th>ID</th>
<th><a href="?ordem=registro&turma={$turma}&turno={$turno}&periodo_atual={$periodo_atual}">Registro</a></th>
<th><a href="?ordem=nome&turma={$turma}&turno={$turno}&periodo_atual={$periodo_atual}">Nome</a></th>
<th><a href="?ordem=turno&turma={$turma}&turno={$turno}&periodo_atual={$periodo_atual}">Turno</a></th>
<th><a href="?ordem=periodo&turma={$turma}&turno={$turno}&periodo_atual={$periodo_atual}">Int. SeSo</a></th>
<th><a href="?ordem=etica&turma={$turma}&turno={$turno}&periodo_atual={$periodo_atual}">Ética</a></th>
<th><a href="?ordem=periodo_estagio&turma={$turma}&turno={$turno}&periodo_atual={$periodo_atual}">Estágio</a></th>
<th><a href="?ordem=nivel&turma={$turma}&turno={$turno}&periodo_atual={$periodo_atual}">Nivel</a></th>
<th><a href="?ordem=periodo_tcc&turma={$turma}&turno={$turno}&periodo_atual={$periodo_atual}">TCC</a></th> 
<th><a href="?ordem=tempo_total&turma={$turma}&turno={$turno}&periodo_atual={$periodo_atual}">Tempo</a></th> 
</tr>

{assign var = "i" value = 1}
{section name=item loop=$alunos}
{if $color == 0}
	<tr class="resaltado" id="resaltado">
	{assign var = "color" value = 1}
{else}
	<tr class="natural" id="natural">
	{assign var = "color" value = 0}
{/if}
<td style="text-align:right">{$i++}</td>
<td style="text-align:center">{$alunos[item].registro}</td>
{if $alunos[item].outro_periodo}
<td style="text-align:left">{$alunos[item].nome}&nbsp<a href='?turma={$alunos[item].outro_periodo}'><span style='font-size:xx-small;border:solid 1px;vertical-align:super;'>{$alunos[item].outro_periodo}</span></a></td>
{else}
<td style="text-align:left">{$alunos[item].nome}</td>
{/if}
<td style="text-align:center">{$alunos[item].turno}</td>
<td style="text-align:center">{$alunos[item].intro_seso}</td>
<td style="text-align:center">{$alunos[item].etica}</td>
{if $alunos[item].busca_estagio}
<td style="text-align:center"><a href='ver-aluno.php?id_aluno={$alunos[item].id_registro}'>Busca</a></td>
{else}
<td style="text-align:center"><a href='../alunos/exibir/ver_cada.php?id_aluno={$alunos[item].id_registro}'>{$alunos[item].periodo_estagio}</a></td>
{/if}
<td style="text-align:center"><a href='../alunos/exibir/ver_cada.php?id_aluno={$alunos[item].id_registro}'>{$alunos[item].nivel}</a></td>
<td style="text-align:center"><a href="../../../tcc/monografia/visualizar/ver_monografia.php?codigo={$alunos[item].tcc}">{$alunos[item].periodo_tcc}</td>

{if $alunos[item].tempo_total}
	{if ($alunos[item].turno eq 'N' and $alunos[item].tempo_total < '10') or ($alunos[item].turno eq 'D' and $alunos[item].tempo_total < '8')}
	<td style="text-align:center; color:red">{$alunos[item].tempo_total}</td>
	{else}
	<td style="text-align:center">{$alunos[item].tempo_total}</td>
	{/if}
{/if}

</tr>
{/section}
</table>
</div>

</body>
</html>