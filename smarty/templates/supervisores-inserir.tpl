<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cadastro de Supervisor</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>

<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Supervisores</p>
			<h1>Inserir Supervisor<span>Cadastro de um novo supervisor de campo</span></h1>
		</div>
	</header>

	<div class="action-row" style="margin-top:0;">
		<a class="btn btn-secondary" href="listar.php">Voltar para a listagem</a>
	</div>

	<form name="supervisor" action="inserir.php" method="post">

	<table class="ficha card-table">
	<tbody>

	<tr>
	<td><label for="nome">Nome *</label></td>
	<td><input type="text" name="nome" id="nome" size="50" maxlength="70" required></td>
	</tr>

	<tr>
	<td><label for="cress">CRESS</label></td>
	<td><input type="text" name="cress" id="cress" size="10" maxlength="10"></td>
	</tr>

	<tr>
	<td><label for="cpf">CPF</label></td>
	<td><input type="text" name="cpf" id="cpf" size="15" maxlength="15"></td>
	</tr>

	<tr>
	<td><label for="email">E-mail</label></td>
	<td><input type="text" name="email" id="email" size="50" maxlength="255"></td>
	</tr>

	<tr>
	<td><label for="telefone">Telefone</label></td>
	<td>(<input type="text" name="codigo_telefone" id="codigo_telefone" size="2" maxlength="2" value="21">)
	<input type="text" name="telefone" id="telefone" size="15" maxlength="15"></td>
	</tr>

	<tr>
	<td><label for="celular">Celular</label></td>
	<td>(<input type="text" name="codigo_celular" id="codigo_celular" size="2" maxlength="2" value="21">)
	<input type="text" name="celular" id="celular" size="15" maxlength="15"></td>
	</tr>

	<tr>
	<td><label for="endereco">Endereço</label></td>
	<td><input type="text" name="endereco" id="endereco" size="60" maxlength="100"></td>
	</tr>

	<tr>
	<td><label for="bairro">Bairro</label></td>
	<td><input type="text" name="bairro" id="bairro" size="30" maxlength="30"></td>
	</tr>

	<tr>
	<td><label for="municipio">Município</label></td>
	<td><input type="text" name="municipio" id="municipio" size="30" maxlength="30"></td>
	</tr>

	<tr>
	<td><label for="cep">CEP</label></td>
	<td><input type="text" name="cep" id="cep" size="9" maxlength="9"></td>
	</tr>

	<tr>
	<td><label for="escola">Escola de formação</label></td>
	<td><input type="text" name="escola" id="escola" size="50" maxlength="70"></td>
	</tr>

	<tr>
	<td><label for="ano_formacao">Ano de formação</label></td>
	<td><input type="text" name="ano_formacao" id="ano_formacao" size="4" maxlength="4"></td>
	</tr>

	<tr>
	<td><label for="cargo">Cargo</label></td>
	<td><input type="text" name="cargo" id="cargo" size="25" maxlength="25"></td>
	</tr>

	<tr>
	<td><label for="regiao">Região (CRESS)</label></td>
	<td><input type="text" name="regiao" id="regiao" size="3" maxlength="2" value="7"></td>
	</tr>

	<tr>
	<td><label for="observacoes">Observações</label></td>
	<td><textarea name="observacoes" id="observacoes" rows="4" cols="60"></textarea></td>
	</tr>

	<tr>
	<td><label for="instituicao_id">Instituição vinculada (opcional)</label></td>
	<td>
	<select name="instituicao_id" id="instituicao_id">
	<option value="0" selected>-- Nenhuma --</option>
	{foreach from=$instituicoes item=inst}
	<option value="{$inst.id}" {if $inst.id == $instituicao_id}selected="selected"{/if}>{$inst.instituicao|truncate:50}</option>
	{/foreach}
	</select>
	<br><span class="card-note">(se escolhida, o supervisor já fica vinculado à instituição)</span>
	</td>
	</tr>

	<tr class="rodape">
	<td colspan="2">
	<button type="submit" name="confirmar" class="btn">Salvar supervisor</button>
	<input type="button" value="Cancelar" onclick="history.back()">
	</td>
	</tr>

	</tbody>
	</table>

	</form>

</main>

</body>
</html>
