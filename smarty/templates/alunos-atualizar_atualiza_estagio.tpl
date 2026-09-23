<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>Atualiza estagio</title>
  <meta content="author" name="Luis Acosta">
<style type="text/css">
@import url("../../estagio.css");
</style>

{literal}

<script language="JavaScript" type="text/javascript" src="../../lib/jquery.js">
</script>
<script type="text/javascript">
$(document).ready(function() {
	$("#instituicao_id").change(function() {
	$("#supervisor_id").html("<option value='sda'>Procurando :::::::</option>");
	$.post('exibir_super.php',
	{ id_estagio : $(this).val() },
	function(resposta){
		$("select[@name=supervisor_id]").html(resposta);
		}
		);
	});
});
</script>

{/literal}
</head>

<body style="direction: ltr;">

{if $atualizar_estagio == 1}
    <p style="background-color:#e7e1ae; text-align:center; font-weight:bold; font-size:14px">Atualizar est&aacute;gio</p>
{/if}

<form action="atualiza.php" name="atualizar_aluno" method="post">

<div align="center">
<table border="1">
<tbody>

<tr>
<th colspan="2">Aluno</th>
</tr>

<!--
Registro
//-->
<tr>
<td>Registro:</td>
<td>
<input type="text" maxlength="9" size="9" name="registro" value={$registro}>
</td>
</tr>

<!--
Nome
//-->
<tr>
<td>Nome:</td>
<td>
<input type="text" maxlength="50" size="50" name="nome" value="{$nome_aluno}">
</td>
</tr>

<!--
Termo de compromisso
//-->
<tr>
<td>Termo compromisso</td>
<td>
{if $tc eq 0}
	Sim <input type="radio" name="tc" id="tc" value="1">
	Nao <input type="radio" name="tc" id="tc" value="0" checked>
{else}
	Sim <input type="radio" name="tc" id="tc" value="1" checked>
	Nao <input type="radio" name="tc" id="tc" value="0">
{/if}
</td>
</tr>

<!--
Periodo
//-->
<tr>
<td>Período</td>
<td>
<input type="text" maxlength="6" size="6" name="periodo" value="{$periodo}">
</td>
</tr>

<!--
Nivel de estagio
//-->
<tr>
<td>Nivel:</td>
{if $nivel eq 1}
<td>
Estágio I   <input type="radio" name="nivel" value="1" checked>
Estágio II  <input type="radio" name="nivel" value="2">
Estágio III <input type="radio" name="nivel" value="3">
Estágio IV  <input type="radio" name="nivel" value="4">
</td>
{elseif $nivel eq 2}
<td>
Estágio I   <input type="radio" name="nivel" value="1">
Estágio II  <input type="radio" name="nivel" value="2" checked>
Estágio III <input type="radio" name="nivel" value="3">
Estágio IV  <input type="radio" name="nivel" value="4">
</td>
{elseif $nivel eq 3}
<td>
Estágio I   <input type="radio" name="nivel" value="1">
Estágio II  <input type="radio" name="nivel" value="2">
Estágio III <input type="radio" name="nivel" value="3" checked>
Estágio IV  <input type="radio" name="nivel" value="4">
</td>
{elseif $nivel eq 4}
<td>
Estágio I   <input type="radio" name="nivel" value="1">
Estágio II  <input type="radio" name="nivel" value="2">
Estágio III <input type="radio" name="nivel" value="3">
Estágio IV  <input type="radio" name="nivel" value="4" checked>
</td>
{else}
<td>
Estágio I   <input type="radio" name="nivel" value="1" checked>
Estágio II  <input type="radio" name="nivel" value="2">
Estágio III <input type="radio" name="nivel" value="3">
Estágio IV  <input type="radio" name="nivel" value="4">
</td>
{/if}
</tr>

<!--
Instituição
//-->
<tr>
<td>Instituição:</td>

<td>
<select id="instituicao_id" name="instituicao_id" size="1" return onchange="seleciona_supervisor();">
<option value="{$instituicao_id}" selected>{$nome_instituicao|truncate:50}</option>
{section name=elemento loop=$instituicoes}
<option value={$instituicoes[elemento].instituicao_id}>
{$instituicoes[elemento].instituicao|truncate:50}</option>
{/section}
</select>
</td>

</tr>

<tr>
<td>Supervisor: </td>
<td>
<select id='supervisor_id' name='supervisor_id' size=1>
<option id ='opcoes' value='{$supervisor_id}'>{$nome_supervisor}</option>
</select>
</td>
</tr>

<!--
Professor
//-->
<tr>
<td>Professor:</td>

<td>
<select id="professor_id" name="professor_id" size="1">
<option value={$professor_id} selected>{$nome_professor|truncate:50}</option>
{section name=elemento loop=$professores}
<option value={$professores[elemento].professor_id}>
{$professores[elemento].professor|truncate:50}</option>
{/section}
</select>
</td>
</tr>

<!--
Avaliacao
//-->
<tr>
<td>Avaliação</td>
<td>
Nota (decimal): <input type="text" name="nota" id="nota" size="5" maxlength="5" value="{$nota}">
Carga horaria (inteiro): <input type="text" name="ch" id="ch" size="5" maxlength="5" value="{$ch}">
</td>
</tr>

<tr>
<td colspan="2" class="coluna_centralizada">
<input type="hidden" name="estagiario_id" value="{$estagiario_id}">
<input type="hidden" name="atualizar_estagio" value=1>
<input type="hidden" name="origem" value="{$origem}">
<input type="hidden" name="aluno_id" value="{$aluno_id}">
<input type="hidden" name="acao" value="1">
<input type="submit" name="submit" value="Confirma">
</td>
</tr>

</tbody>
</table>
</div>

</form>

</body>
</html>