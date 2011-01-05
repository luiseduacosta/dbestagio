<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Cadastro de instituições de estágio</title>
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
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

{literal}
<script language="JavaScript" type="text/javascript">

function janelaAviso() {
	var insere = document.getElementById("instituicaoConveniada").value;
	if (insere != "") {
		var texto = document.getElementById("aviso");
		texto.setAttribute("style","font-weight:bold; background-color=yellow; color: red");
		texto.style.cssText = "font-weight:bold; background-color:yellow; color:red";
		texto.innerHTML = "Verifique se a instituição está conveniada com a UFRJ";
		insere="";
		}
	return true;
	}

function verificaConvenio() {
	for(i=0; i<2; i++) {
   		// alert("convenio" +i);
   		if (document.getElementById("convenio"+i).checked) {
   			if (i==0) {
   				alert("Instituição não conveniada?");
   				return false;
   				} // Fecha if
   			} // Fecha if
   		} // Fecha for
   		
	var email = document.getElementById("email").value;
	alert(email);
	if(email == "") {
		alert("Sem o e-mail não será possível enviar e-mail para a instituicao");
	}
		
	return true;
	} // Fecha function

</script>
{/literal}

</head>

<body onLoad="return janelaAviso()">

<body>

{include file="mural_menu.tpl"}

<form name="aviso" id="aviso" action="#" method="post" enctype="text/plain">
<input type="hidden" name="instituicaoConveniada" id="instituicaoConveniada" value="{$aviso}">
</form>

<span id="aviso"></span>

<form name="cadastro" id="cadastro" action="mural_inserir.php" method="post" onSubmit="return verificaConvenio();">

<div align="center">
<table border="1">
<caption>Oferta de vagas de Estágio {$periodo_atual}</caption>
<tbody>

<tr>
<td>Convênio:</td>
<td>
<input type="radio" name="convenio" id="convenio0" value="0" checked>Não
<input type="radio" name="convenio" id="convenio1" value="1">Sim
</td>
</tr>

<tr>
<td>Instituição:</td>
<td>
<select name='id_estagio' size='1'>
{section name=i loop=$instituicoes}
<option value={$instituicoes[i].id}>{$instituicoes[i].instituicao|truncate:70}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>Vagas:</td>
<td><input type="text" name="vagas" id="vagas" size="3" maxlength="3">Digitar somente números</td>
</tr>

<tr>
<td>Benefícios:</td>
<td><input type="text" name="beneficios" id="beneficios" size="50" maxlength="50"></td>
</tr>

<tr>
<td>Estágio no final de semana:</td>
<td>
<input type="radio" name="final_de_semana" id="final_de_semana0" value="0" checked>Não
<input type="radio" name="final_de_semana" id="final_de_semana1" value="1">Sim
<input type="radio" name="final_de_semana" id="final_de_semana2" value="2">Parcialmente
</td>
</tr>

<tr>
<td>Carga horária semanal</td>
<td><input type="text" name="cargaHoraria" id="cargaHoraria" size="2"  maxlength="2">Digitar somente números</td>
</tr>

<tr>
<td>Requisitos</td>
<td><textarea name="requisitos" id="requisitos" rows="3" cols="70"></textarea></td>
</tr>

<tr>
<td>Área da disciplina:</td>
<td>
<select name="id_area" id="id_area">
{html_options values=$id_areas output=$areas}
</select>
</td>
</tr>

<tr>
<td>Horário da disciplina</td>
<td>
<input type="radio" name="horario" id="horarioD" value="D" checked>Diurno
<input type="radio" name="horario" id="horarioN" value="N">Noturno
<input type="radio" name="horario" id="horarioA" value="A">Ambos
</td>
</tr>

<tr>
<td>Professor</td>
<td>
<select name="id_professor" id="id_professor">
{html_options values=$id_professores output=$professores}
</select>
</td>
</tr>

<tr>
<td>Inscrições na Coordenação de Estágio até:</td>
<td>Dia:
<select name="diaInscricao">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
</select>
Mês:
<select name="mesInscricao">
<option value="1">Janeiro</option>
<option value="2">Fevereiro</option>
<option value="3">Março</option>
<option value="4">Abril</option>
<option value="5">Maio</option>
<option value="6">Junho</option>
<option value="7">Julho</option>
<option value="8">Agosto</option>
<option value="9">Setembro</option>
<option value="10">Outubro</option>
<option value="11">Novembro</option>
<option value="12">Dezembro</option>
</select>
Ano:
<select name="anoInscricao">
<option value="2007">2007</option>
<option value="2008">2008</option>
<option value="2009">2009</option>
<option value="2010">2010</option>
</select>
</td>
</tr>

<tr>
<td>Data da seleção</td>
<td>Dia:
<select name="dia">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="11">11</option>
<option value="12">12</option>
<option value="13">13</option>
<option value="14">14</option>
<option value="15">15</option>
<option value="16">16</option>
<option value="17">17</option>
<option value="18">18</option>
<option value="19">19</option>
<option value="20">20</option>
<option value="21">21</option>
<option value="22">22</option>
<option value="23">23</option>
<option value="24">24</option>
<option value="25">25</option>
<option value="26">26</option>
<option value="27">27</option>
<option value="28">28</option>
<option value="29">29</option>
<option value="30">30</option>
<option value="31">31</option>
</select>
Mês:
<select name="mes">
<option value="1">Janeiro</option>
<option value="2">Fevereiro</option>
<option value="3">Março</option>
<option value="4">Abril</option>
<option value="5">Maio</option>
<option value="6">Junho</option>
<option value="7">Julho</option>
<option value="8">Agosto</option>
<option value="9">Setembro</option>
<option value="10">Outubro</option>
<option value="11">Novembro</option>
<option value="12">Dezembro</option>
</select>
Ano:
<select name="ano">
<option value="2007">2007</option>
<option value="2008">2008</option>
<option value="2009">2009</option>
<option value="2010">2010</option>
<option value="2011">2011</option>
<option value="2012">2012</option>
<option value="2013">2013</option>
<option value="2014">2014</option>
<option value="2015">2015</option>
</select>
Horário:
<input type="text" name="horarioSelecao" id="horarioSelecao" size="5" value="00:00">
</td>
</tr>

<tr>
<td>Local da seleção</td>
<td><input type="text" name="localSelecao" id="localSelecao" size="50" maxlength="70"></td>
</tr>

<tr>
<td>Forma de seleção</td>
<td>
<input type="radio" name="formaSelecao" id="formaSelecao0" value="0" checked>Entrevista
<input type="radio" name="formaSelecao" id="formaSelecao1" value="1">CR
<input type="radio" name="formaSelecao" id="formaSelecao2" value="2">Prova
<input type="radio" name="formaSelecao" id="formaSelecao3" value="3">Outras (especificar em outras informações)
</td>
</tr>

<tr>
<td>Contato e/ou informações</td>
<td><input type="text" name="contato" id="contato" size="50" maxlength="70"></td>
</tr>

<tr>
	<td>E-mail (para enviar lista de alunos)</td>
	<td><input type="text" name="email" id="email" size="50" maxlength="70"></td>
</tr>

<tr>
<td>Outras informações</td>
<td>
<textarea name="outras" id="outras" rows="5" cols="70"></textarea>
</td>
</tr>

<tr>
<td class="rodape" colspan=2>
<input type="hidden" value={$periodo_atual} name="periodo_atual">
<input type="submit" value="Confirma" name="inserir">
</td>
</tr>

</tbody>
</table>
</div>

</form>

</body>
</html>