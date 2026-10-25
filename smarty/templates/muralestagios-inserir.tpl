<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cadastro de mural de estágio</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">

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
	// alert(email);
	if(email == "") {
		alert("Sem o e-mail não será possível enviar e-mail para a instituicao");
	}
		
	return true;
	} // Fecha function

</script>
{/literal}

</head>

<body id="corpo" onLoad="return janelaAviso()">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Mural</p>
			<h1>Nova oferta de vagas<span>Cadastro de estágio · Período {$periodo}</span></h1>
		</div>
	</header>

	<form name="aviso" id="aviso" action="#" method="post" enctype="text/plain">
	<input type="hidden" name="instituicaoConveniada" id="instituicaoConveniada" value="{$aviso}">
	</form>

	<span id="aviso"></span>

	<form name="cadastro" id="cadastro" action="mural-inserir.php" method="post" onSubmit="return verificaConvenio();">

		<table class="card-table">
		<tbody>

		<tr>
		<th><label for="convenio0">Convênio</label></th>
		<td>
		<label><input type="radio" name="convenio" id="convenio0" value="0" checked> Não</label>
		<label><input type="radio" name="convenio" id="convenio1" value="1"> Sim</label>
		</td>
		</tr>

		<tr>
		<th><label for="instituicao_id">Instituição</label></th>
		<td>
		<select name="instituicao_id" id="instituicao_id" size="1">
		{section name=i loop=$instituicoes}
		<option value={$instituicoes[i].id}>{$instituicoes[i].instituicao|truncate:70}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<th><label for="vagas">Vagas</label></th>
		<td><input type="text" name="vagas" id="vagas" size="3" maxlength="3"> <span class="form-hint">Digitar somente números</span></td>
		</tr>

		<tr>
		<th><label for="beneficios">Benefícios</label></th>
		<td><input type="text" name="beneficios" id="beneficios" size="50" maxlength="50"></td>
		</tr>

		<tr>
		<th><label for="final_de_semana0">Estágio no final de semana</label></th>
		<td>
		<label><input type="radio" name="final_de_semana" id="final_de_semana0" value="0" checked> Não</label>
		<label><input type="radio" name="final_de_semana" id="final_de_semana1" value="1"> Sim</label>
		<label><input type="radio" name="final_de_semana" id="final_de_semana2" value="2"> Parcialmente</label>
		</td>
		</tr>

		<tr>
		<th><label for="carga_horaria">Carga horária semanal</label></th>
		<td><input type="text" name="carga_horaria" id="carga_horaria" size="2" maxlength="2"> <span class="form-hint">Digitar somente números</span></td>
		</tr>

		<tr>
		<th><label for="requisitos">Requisitos</label></th>
		<td><textarea name="requisitos" id="requisitos" rows="3" cols="70"></textarea></td>
		</tr>

		<tr>
		<th><label for="area_id">Área da disciplina</label></th>
		<td>
		<select name="area_id" id="area_id">
		{html_options values=$area_id output=$areas}
		</select>
		</td>
		</tr>

		<tr>
		<th><label for="horarioD">Horário da disciplina</label></th>
		<td>
		<label><input type="radio" name="horario" id="horarioD" value="D" checked> Diurno</label>
		<label><input type="radio" name="horario" id="horarioN" value="N"> Noturno</label>
		<label><input type="radio" name="horario" id="horarioA" value="A"> Ambos</label>
		</td>
		</tr>

		<tr>
		<th><label for="professor_id">Professor</label></th>
		<td>
		<select name="professor_id" id="professor_id">
		{html_options values=$professor_id output=$professores}
		</select>
		</td>
		</tr>

		<tr>
		<th>Inscrições na Coordenação de Estágio até</th>
		<td>
		<label for="dia_inscricao">Dia</label>
		<select name="dia_inscricao" id="dia_inscricao">
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
		<label for="mes_inscricao">Mês</label>
		<select name="mes_inscricao" id="mes_inscricao">
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
		<label for="ano_inscricao">Ano</label>
		<select name="ano_inscricao" id="ano_inscricao">
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
		</td>
		</tr>

		<tr>
		<th>Data da seleção</th>
		<td>
		<label for="dia_selecao">Dia</label>
		<select name="dia_selecao" id="dia_selecao">
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
		<label for="mes_selecao">Mês</label>
		<select name="mes_selecao" id="mes_selecao">
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
		<label for="ano_selecao">Ano</label>
		<select name="ano_selecao" id="ano_selecao">
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

		<label for="horario_selecao">Horário</label>
		<input type="text" name="horario_selecao" id="horario_selecao" size="5" value="00:00">
		</td>
		</tr>

		<tr>
		<th><label for="local_selecao">Local da seleção</label></th>
		<td><input type="text" name="local_selecao" id="local_selecao" size="50" maxlength="70"></td>
		</tr>

		<tr>
		<th><label for="forma_selecao0">Forma de seleção</label></th>
		<td>
		<label><input type="radio" name="forma_selecao" id="forma_selecao0" value="0" checked> Entrevista</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao1" value="1"> CR</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao2" value="2"> Prova</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao3" value="3"> Outras (especificar em outras informações)</label>
		</td>
		</tr>

		<tr>
		<th><label for="contato">Contato e/ou informações</label></th>
		<td><input type="text" name="contato" id="contato" size="50" maxlength="70"></td>
		</tr>

		<tr>
		<th><label for="email">E-mail (para enviar lista de alunos)</label></th>
		<td><input type="text" name="email" id="email" size="50" maxlength="70"></td>
		</tr>

		<tr>
		<th><label for="outras">Outras informações</label></th>
		<td><textarea name="outras" id="outras" rows="5" cols="70"></textarea></td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="hidden" value={$periodo} name="periodo">
		<input type="submit" value="Confirma" name="inserir">
		</td>
		</tr>

		</tbody>
		</table>

	</form>

</main>

</body>
</html>
