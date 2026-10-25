<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Modificar oferta de estágio</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>

<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Mural</p>
			<h1>Modificar oferta de vagas<span>Período {$periodo}</span></h1>
		</div>
	</header>

	<form name="atualiza_instituicao" action="mural-atualiza.php" method="post">

		<table class="card-table">
		<tbody>

		<tr>
		<th>Id instituição</th>
		<td>{$muralestagio_id}</td>
		</tr>

		<tr>
		<th>Convênio</th>
		<td>
		{if $convenio == 0}
		<label><input type="radio" name="convenio" id="convenio" value="0" checked="{$convenio}"> Não</label>
		<label><input type="radio" name="convenio" id="convenio" value="1"> Sim</label>
		{elseif $convenio == 1}
		<label><input type="radio" name="convenio" id="convenio" value="0"> Não</label>
		<label><input type="radio" name="convenio" id="convenio" value="1" checked="{$convenio}"> Sim</label>
		{/if}
		</td>
		</tr>

		<tr>
		<th><label for="instituicao">Instituição</label></th>
		<td><input type="text" name="instituicao" id="instituicao" size="50" maxlength="100" value="{$instituicao}"></td>
		</tr>

		<tr>
		<th><label for="vagas">Vagas</label></th>
		<td><input type="text" name="vagas" id="vagas" size="3" maxlength="3" value="{$vagas}"> <span class="form-hint">Digite somente números</span></td>
		</tr>

		<tr>
		<th><label for="beneficios">Benefícios</label></th>
		<td><input type="text" name="beneficios" id="beneficios" size="50" maxlength="50" value="{$beneficios}"></td>
		</tr>

		<tr>
		<th>Final de semana</th>
		<td>
		{if $final_de_semana == 0}
		<label><input type="radio" name="final_de_semana" id="final_de_semana" value="0" checked="{$final_de_semana}"> Não</label>
		<label><input type="radio" name="final_de_semana" id="final_de_semana" value="1"> Sim</label>
		<label><input type="radio" name="final_de_semana" id="final_de_semana" value="2"> Parcialmente</label>
		{elseif $final_de_semana == 1}
		<label><input type="radio" name="final_de_semana" id="final_de_semana" value="0"> Não</label>
		<label><input type="radio" name="final_de_semana" id="final_de_semana" value="1" checked="{$final_de_semana}"> Sim</label>
		<label><input type="radio" name="final_de_semana" id="final_de_semana" value="2"> Parcialmente</label>
		{elseif $final_de_semana == 2}
		<label><input type="radio" name="final_de_semana" id="final_de_semana" value="0"> Não</label>
		<label><input type="radio" name="final_de_semana" id="final_de_semana" value="1"> Sim</label>
		<label><input type="radio" name="final_de_semana" id="final_de_semana" value="2" checked="{$final_de_semana}"> Parcialmente</label>
		{/if}
		</td>
		</tr>

		<tr>
		<th><label for="carga_horaria">Carga horária</label></th>
		<td><input type="text" name="carga_horaria" id="carga_horaria" size="2" maxlength="2" value="{$cargaHoraria}"> <span class="form-hint">Digite somente números</span></td>
		</tr>

		<tr>
		<th><label for="requisitos">Requisitos</label></th>
		<td><textarea name="requisitos" id="requisitos" rows="3" cols="70">{$requisitos}</textarea></td>
		</tr>

		<tr>
		<th><label for="area_id">Área da disciplina</label></th>
		<td>
		<select name="area_id" id="area_id" size="1">
		<option value={$area_id}>{$area}</option>
		{section name=item loop=$areas}
		<option value={$areas[item].id_areas}>{$areas[item].areas}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<th><label for="professor_id">Professor</label></th>
		<td>
		<select name="professor_id" id="professor_id" size="1">
		<option value={$professor_id}>{$professor}</option>
		{section name=item loop=$professores}
		<option value={$professores[item].id_professores}>{$professores[item].professores}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<th>Horário da disciplina</th>
		<td>
		{if $horario == "D"}
		<label><input type="radio" name="horario" id="horario" value="D" checked="{$horario}"> Diurno</label>
		<label><input type="radio" name="horario" id="horario" value="N"> Noturno</label>
		<label><input type="radio" name="horario" id="horario" value="A"> Ambos</label>
		{elseif $horario == "N"}
		<label><input type="radio" name="horario" id="horario" value="D"> Diurno</label>
		<label><input type="radio" name="horario" id="horario" value="N" checked="{$horario}"> Noturno</label>
		<label><input type="radio" name="horario" id="horario" value="A"> Ambos</label>
		{elseif $horario == "A"}
		<label><input type="radio" name="horario" id="horario" value="D"> Diurno</label>
		<label><input type="radio" name="horario" id="horario" value="N" checked="{$horario}"> Noturno</label>
		<label><input type="radio" name="horario" id="horario" value="A"> Ambos</label>
		{/if}
		</td>
		</tr>

		<tr>
		<th><label for="data_inscricao">Inscrições na Coordenação de Estágio até</label></th>
		<td><input type="text" name="data_inscricao" id="data_inscricao" size="15" maxlength="15" value="{$dataInscricao}"> <span class="form-hint">Formato: dd-mm-aaaa</span></td>
		</tr>

		<tr>
		<th>Data da seleção</th>
		<td>
		<input type="text" name="data_selecao" id="data_selecao" size="15" maxlength="15" value="{$dataSelecao}">
		<label for="horario_selecao">Horário</label>
		<input type="text" name="horario_selecao" id="horario_selecao" size="5" maxlength="5" value="{$horarioSelecao}">
		<span class="form-hint">Formato hh:mm</span>
		</td>
		</tr>

		<tr>
		<th><label for="local_selecao">Local da seleção</label></th>
		<td><input type="text" name="local_selecao" id="local_selecao" size="50" maxleghth="70" value="{$localSelecao}"></td>
		</tr>

		<tr>
		<th>Forma de seleção</th>
		<td>
		{if $formaSelecao == 0}
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="0" checked="{$formaSelecao}"> Entrevista</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="1"> CR</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="3"> Prova</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="3"> Outra</label>
		{elseif $formaSelecao == 1}
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="0"> Entrevista</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="1" checked="{$formaSelecao}"> CR</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="2"> Prova</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="3"> Outra</label>
		{elseif $formaSelecao == 2}
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="0"> Entrevista</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="1"> CR</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="2" checked="{$formaSelecao}"> Prova</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="3"> Outra</label>
		{elseif $formaSelecao == 3}
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="0"> Entrevista</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="1"> CR</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="2"> Prova</label>
		<label><input type="radio" name="forma_selecao" id="forma_selecao" value="3" checked="{$formaSelecao}"> Outra</label>
		{/if}
		</td>
		</tr>

		<tr>
		<th><label for="contato">Contato</label></th>
		<td><input type="text" name="contato" id="contato" size="50" maxlength="70" value="{$contato}"></td>
		</tr>

		<tr>
		<th><label for="email">E-mail (para enviar lista de alunos)</label></th>
		<td><input type="text" name="email" id="email" size="50" maxlength="70" value="{$email}"></td>
		</tr>

		<tr>
		<th><label for="outras">Outras informações</label></th>
		<td><textarea name="outras" id="outras" rows="5" cols="70">{$outras}</textarea></td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="hidden" name="periodo" value="{$periodo}">
		<input type="hidden" name="muralestagio_id" value="{$muralestagio_id}">
		<input type="submit" value="Confirma" name="inserir">
		</td>
		</tr>

		</tbody>
		</table>

	</form>

</main>

</body>
</html>
