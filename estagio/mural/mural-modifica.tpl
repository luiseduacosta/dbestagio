<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Instituição</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="author" content="Luis Acosta">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
<style type="text/css">
@import url("../estagio.css");
</style>
<link rel="stylesheet" type="text/css" href="../lib/mygosumenu/1.0/example1.css" />
<script type="text/javascript" src="../lib/mygosumenu/ie5.js"></script>
<script type="text/javascript" src="../lib/mygosumenu/1.0/DropDownMenu1.js"></script>
</head>

<body>

{include file="mural_menu.tpl"}

<form name="atualiza_instituicao" action="mural-atualiza.php" method="post">

<div align="center">
<table border="1">
<tbody>

<tr>
<td>Id instituição</td>
<td>{$id_instituicao}</td>
</tr>

<tr>
<td>Convênio</td>
<td>
{if $convenio == 0}
    <input type="radio" name="convenio" id="convenio" value="0" checked="{$convenio}">Não
    <input type="radio" name="convenio" id="convenio" value="1">Sim
{elseif $convenio == 1}
    <input type="radio" name="convenio" id="convenio" value="0">Não
    <input type="radio" name="convenio" id="convenio" value="1" checked="{$convenio}">Sim
{/if}
</td>
</tr>

<tr>
<td>Instituição</td>
<td><input type="text" name="instituicao" id="instituicao" size="50" maxlength="100" value="{$instituicao}"></td>
</tr>

<tr>
<td>Vagas</td>
<td><input type="text" name="vagas" id="vagas" size="3" maxlength="3" value="{$vagas}">Digite somente números</td>
</tr>

<tr>
<td>Benefícios</td>
<td><input type="text" name="beneficios" id="beneficios" size="50"  maxlength="50" value="{$beneficios}"></td>
</tr>

<tr>
<td>Final de semana</td>
<td>
{if $final_de_semana == 0}
	<input type="radio" name="final_de_semana" id="final_de_semana" value="0" checked="{$fianl_de_semana}">Não
	<input type="radio" name="final_de_semana" id="final_de_semana" value="1">Sim
	<input type="radio" name="final_de_semana" id="final_de_semana" value="2">Parcialmente
{elseif $final_de_semana == 1}
	<input type="radio" name="final_de_semana" id="final_de_semana" value="0">Não
	<input type="radio" name="final_de_semana" id="final_de_semana" value="1" checked="{$final_de_semana}">Sim
	<input type="radio" name="final_de_semana" id="final_de_semana" value="2">Parcialmente
{elseif $final_de_semana == 2}
	<input type="radio" name="final_de_semana" id="final_de_semana" value="0">Não
	<input type="radio" name="final_de_semana" id="final_de_semana" value="1">Sim
	<input type="radio" name="final_de_semana" id="final_de_semana" value="2" checked="{$final_de_semana}">Parcialmente
{/if}
</td>
</tr>

<tr>
<td>Carga horária</td>
<td><input type="text" name="cargaHoraria" id="cargaHoraria" size="2"  maxlength="2" value="{$cargaHoraria}">Digite somente números</td>
</tr>

<tr>
<td>Requisitos</td>
<td><textarea name="requisitos" id="requisitos" rows="3" cols="70">{$requisitos}</textarea></td>
</tr>

<tr>
<td>Área da disciplina</td>
<td>
<select name="id_area" id="id_area" size="1">
<option value={$id_area}>{$area}<option>
{section name=item loop=$areas}
<option value={$areas[item].id_areas}>{$areas[item].areas}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Professor</td>
<td>
<select name="id_professor" id="id_professor" size="1">
<option value={$id_professor}>{$professor}</option>
{section name=item loop=$professores}
<option value={$professores[item].id_professores}>{$professores[item].professores}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Horário da disciplina</td>
<td>
{if $horario == "D"}
	<input type="radio" name="horario" id="horario" value="D" checked="{$horario}">Diurno
	<input type="radio" name="horario" id="horario" value="N">Noturno
	<input type="radio" name="horario" id="horario" value="A">Ambos
{elseif $horario == "N"}
	<input type="radio" name="horario" id="horario" value="D">Diurno
	<input type="radio" name="horario" id="horario" value="N" checked="{$horario}">Noturno
	<input type="radio" name="horario" id="horario" value="A">Ambos
{elseif $horario == "A"}
	<input type="radio" name="horario" id="horario" value="D">Diurno
	<input type="radio" name="horario" id="horario" value="N" checked="{$horario}">Noturno
	<input type="radio" name="horario" id="horario" value="A">Ambos
{/if}
</td>
</tr>

<tr>
<td>Inscrições na Coordenação de Estágio atá:</td>
<td><input type="text" name="dataInscricao" id="dataInscricao" size="15" maxlength="15" value="{$dataInscricao}">
Formato: dd-mm-aaaa</td>
</tr>

<tr>
<td>Data da seleção</td>
<td>
<input type="text" name="dataSelecao" id="dataSelecao" size="15" maxlength="15" value="{$dataSelecao}">
Horário:
<input type="text" name="horarioSelecao" id="horarioSelecao" size="5" maxlength="5" value="{$horarioSelecao}">
Formato hh:mm</td>
</tr>

<tr>
<td>Local da seleção</td>
<td><input type="text" name="localSelecao" id="localSelecao" size="50" maxleghth="70" value="{$localSelecao}"></td>
</tr>

<tr>
<td>Forma de seleção</td>
<td>
{if $formaSelecao == 0}
	<input type="radio" name="formaSelecao" id="formaSelecao" value="0" checked="{$formaSelecao}">Entrevista
	<input type="radio" name="formaSelecao" id="formaSelecao" value="1">CR
	<input type="radio" name="formaSelecao" id="formaSelecao" value="3">Prova
	<input type="radio" name="formaSelecao" id="formaSelecao" value="3">Outra
{elseif $formaSelecao == 1}
	<input type="radio" name="formaSelecao" id="formaSelecao" value="0">Entrevista
	<input type="radio" name="formaSelecao" id="formaSelecao" value="1" checked="{$formaSelecao}">CR
	<input type="radio" name="formaSelecao" id="formaSelecao" value="2">Prova
	<input type="radio" name="formaSelecao" id="formaSelecao" value="3">Outra
{elseif $formaSelecao == 2}
	<input type="radio" name="formaSelecao" id="formaSelecao" value="0">Entrevista
	<input type="radio" name="formaSelecao" id="formaSelecao" value="1">CR
	<input type="radio" name="formaSelecao" id="formaSelecao" value="2" checked="{$formaSelecao}">Prova
	<input type="radio" name="formaSelecao" id="formaSelecao" value="3">Outra
{elseif $formaSelecao == 3}
	<input type="radio" name="formaSelecao" id="formaSelecao" value="0">Entrevista
	<input type="radio" name="formaSelecao" id="formaSelecao" value="1">CR
	<input type="radio" name="formaSelecao" id="formaSelecao" value="2">Prova
	<input type="radio" name="formaSelecao" id="formaSelecao" value="3" checked="{$formaSelecao}">Outra
{/if}
</td>
</tr>

<tr>
<td>Contato</td>
<td><input type="text" name="contato" id="contato" size="50" maxlength="70" value="{$contato}"></td>
</tr>

<tr>
<td>E-mail (para enviar lista de alunos)</td>
<td><input type="text" name="email" id="email" size="50" maxlength="70" value="{$email}"></td>
</tr>

<tr>
<td>Outras informações</td>
<td><textarea name="outras" id="outra" rows="5" cols="70">{$outras}</textarea></td>
</tr>

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="hidden" name="periodo" value="{$periodo}">
<input type="hidden" name="id_instituicao" value="{$id_instituicao}">
<input type="submit" value="Confirma" name="inserir">
</td>
</tr>

</tbody>
</table>
</div>

</form>

</body>

</html>