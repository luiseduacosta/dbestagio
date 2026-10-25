<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Instituição</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Instituições</p>
			<h1>Modificar instituição<span>{$nome_instituicao}</span></h1>
		</div>
	</header>

	<form name="atualiza_instituicao" action="atualizar.php" method="post">

		<table class="card-table">
		<tbody>

		<tr>
		<th>Id instituição</th>
		<td>{$instituicao_id}</td>
		</tr>

		<tr>
		<th><label for="nome_instituicao">Instituição</label></th>
		<td>
		<input type="text" name="nome_instituicao" id="nome_instituicao" size="50" value="{$nome_instituicao}">
		</td>
		</tr>

		<tr>
		<th><label for="endereco_instituicao">Endereço</label></th>
		<td>
		<input type="text" name="endereco_instituicao" id="endereco_instituicao" size="50" value="{$endereco_instituicao}">
		</td>
		</tr>

		<tr>
		<th><label for="cep_instituicao">CEP</label></th>
		<td>
		<input type="text" name="cep_instituicao" id="cep_instituicao" size="9" value="{$cep_instituicao}">
		</td>
		</tr>

		<tr>
		<th><label for="telefone_instituicao">Telefone</label></th>
		<td>
		<input type="text" name="telefone_instituicao" id="telefone_instituicao" size="35" value="{$telefone_instituicao}">
		</td>
		</tr>

		<tr>
		<th><label for="beneficio_instituicao">Benefícios</label></th>
		<td>
		<input type="text" name="beneficio_instituicao" id="beneficio_instituicao" size="50" value="{$beneficio_instituicao}">
		</td>
		</tr>

		<tr>
		<th>Estágio no final de semana</th>

		<td>
		{if $fim_de_semana == 0}
		<label><input type="radio" name="fim_de_semana" value="0" checked> Não</label>
		<label><input type="radio" name="fim_de_semana" value="1"> Sim</label>
		<label><input type="radio" name="fim_de_semana" value="2"> Parcialmente</label>
		{elseif $fim_de_semana == 1}
		<label><input type="radio" name="fim_de_semana" value="0"> Não</label>
		<label><input type="radio" name="fim_de_semana" value="1" checked="{$fim_de_semana}"> Sim</label>
		<label><input type="radio" name="fim_de_semana" value="2"> Parcialmente</label>
		{elseif $fim_de_semana == 2}
		<label><input type="radio" name="fim_de_semana" value="0"> Não</label>
		<label><input type="radio" name="fim_de_semana" value="1"> Sim</label>
		<label><input type="radio" name="fim_de_semana" value="2" checked="{$fim_de_semana}"> Parcialmente</label>
		{/if}
		</td>

		</tr>

		<tr>
		<th><label for="area_instituicao">Área</label></th>

		<td>
		<select name="area_instituicao" id="area_instituicao" size="1">
		<option value={$id_area_instituicao} selected>{$area_instituicao}</option>
		{section name=elementos loop=$matriz_areas}
		<option value={$matriz_areas[elementos].id}>{$matriz_areas[elementos].area}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<th><label for="id_convenio">Convênio</label></th>
		<td>
		<input type="text" name="convenio" id="id_convenio" size="5" value="{$convenio}">
		</td>
		</tr>

		<tr>
		<th>Última turma</th>
		<td>{$turma}</td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="hidden" name="instituicao_id" value="{$instituicao_id}">
		<input type="submit" value="Confirma" name="inserir">
		</td>
		</tr>

		</tbody>
		</table>

	</form>

</main>

</body>
</html>
