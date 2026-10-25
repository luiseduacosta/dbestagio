<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Inserir instituição</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Instituições</p>
			<h1>Nova instituição<span>Cadastro de instituição conveniada</span></h1>
		</div>
	</header>

	<form name="insere_instituicao" action="inserir.php" method="post">

		<table class="card-table">
		<tbody>

		<tr>
		<th><label for="instituicao">Instituição *</label></th>
		<td><input type="text" name="instituicao" id="instituicao" size="60" maxlength="100"></td>
		</tr>

		<tr>
		<th><label for="natureza">Natureza</label></th>
		<td><input type="text" name="natureza" id="natureza" size="30" maxlength="50"></td>
		</tr>

		<tr>
		<th><label for="area_id">Área</label></th>
		<td>
		<select name="area_id" id="area_id" size="1">
		<option value="">-- Selecione --</option>
		{section name=elementos loop=$matriz_areas}
		<option value="{$matriz_areas[elementos].id}">{$matriz_areas[elementos].area}</option>
		{/section}
		</select>
		</td>
		</tr>

		<tr>
		<th><label for="cnpj">CNPJ</label></th>
		<td><input type="text" name="cnpj" id="cnpj" size="18" maxlength="18"></td>
		</tr>

		<tr>
		<th><label for="email">E-mail</label></th>
		<td><input type="text" name="email" id="email" size="60" maxlength="90"></td>
		</tr>

		<tr>
		<th><label for="url">Página web</label></th>
		<td><input type="text" name="url" id="url" size="60" maxlength="100"></td>
		</tr>

		<tr>
		<th><label for="endereco">Endereço</label></th>
		<td><input type="text" name="endereco" id="endereco" size="60" maxlength="105"></td>
		</tr>

		<tr>
		<th><label for="bairro">Bairro</label></th>
		<td><input type="text" name="bairro" id="bairro" size="30" maxlength="30"></td>
		</tr>

		<tr>
		<th><label for="municipio">Município</label></th>
		<td><input type="text" name="municipio" id="municipio" size="30" maxlength="30"></td>
		</tr>

		<tr>
		<th><label for="cep">CEP</label></th>
		<td><input type="text" name="cep" id="cep" size="9" maxlength="9"></td>
		</tr>

		<tr>
		<th><label for="telefone">Telefone</label></th>
		<td><input type="text" name="telefone" id="telefone" size="40" maxlength="50"></td>
		</tr>

		<tr>
		<th><label for="beneficios">Benefícios</label></th>
		<td><input type="text" name="beneficios" id="beneficios" size="50" maxlength="50"></td>
		</tr>

		<tr>
		<th>Estágio no final de semana</th>
		<td>
		<label><input type="radio" name="fim_de_semana" value="0"> Não</label>
		<label><input type="radio" name="fim_de_semana" value="1"> Sim</label>
		<label><input type="radio" name="fim_de_semana" value="2"> Parcialmente</label>
		</td>
		</tr>

		<tr>
		<th><label for="convenio">Convênio</label></th>
		<td><input type="text" name="convenio" id="convenio" size="8" maxlength="11"></td>
		</tr>

		<tr>
		<th><label for="expira">Expira</label></th>
		<td><input type="text" name="expira" id="expira" size="10" maxlength="10"></td>
		</tr>

		<tr>
		<th>Seguro</th>
		<td>
		<label><input type="radio" name="seguro" value="0"> Instituição</label>
		<label><input type="radio" name="seguro" value="1"> UFRJ</label>
		</td>
		</tr>

		<tr>
		<th><label for="observacoes">Observações</label></th>
		<td><textarea rows="4" cols="60" name="observacoes" id="observacoes"></textarea></td>
		</tr>

		<tr>
		<th><label for="supervisores">Supervisores</label></th>
		<td>
		<select name="supervisores[]" id="supervisores" size="6" multiple="multiple">
		{section name=s loop=$supervisores}
		<option value="{$supervisores[s].id}">{$supervisores[s].nome}</option>
		{/section}
		</select>
		<span class="form-hint">Use Ctrl/Shift para selecionar mais de um supervisor existente (opcional).</span>
		</td>
		</tr>

		<tr>
		<th>Novo supervisor</th>
		<td>
		<label for="novo_supervisor_nome">Nome</label>
		<input type="text" name="novo_supervisor_nome" id="novo_supervisor_nome" size="40" maxlength="70">
		<label for="novo_supervisor_cress">Cress</label>
		<input type="text" name="novo_supervisor_cress" id="novo_supervisor_cress" size="10" maxlength="10">
		<label for="novo_supervisor_email">E-mail</label>
		<input type="text" name="novo_supervisor_email" id="novo_supervisor_email" size="30" maxlength="255">
		<span class="form-hint">Opcional: cadastra um supervisor novo e já vincula à instituição.</span>
		</td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="submit" name="inserir" value="Salvar instituição">
		</td>
		</tr>

		</tbody>
		</table>

	</form>

</main>

</body>
</html>
