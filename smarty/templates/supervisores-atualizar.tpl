<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Modificar Supervisor - {$nome}</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>

<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Supervisores</p>
			<h1>Modificar Supervisor<span>{$nome}</span></h1>
		</div>
	</header>

	<div class="action-row" style="margin-top:0;">
		<a class="btn btn-secondary" href="ver_cada.php?supervisor_id={$supervisor_id}">Voltar para o supervisor</a>
	</div>

	<form name="atualiza_supervisor" action="atualizar.php" method="post">
	<input type="hidden" name="supervisor_id" value="{$supervisor_id}">

	<table class="ficha card-table">
	<tbody>

	<tr>
	<td colspan="2"><strong>ID:</strong> {$supervisor_id}</td>
	</tr>

	<tr>
	<td><label for="nome">Nome *</label></td>
	<td><input type="text" name="nome" id="nome" size="50" maxlength="70" value="{$nome}" required></td>
	</tr>

	<tr>
	<td><label for="cress">CRESS</label></td>
	<td><input type="text" name="cress" id="cress" size="10" maxlength="10" value="{$cress}"></td>
	</tr>

	<tr>
	<td><label for="cpf">CPF</label></td>
	<td><input type="text" name="cpf" id="cpf" size="15" maxlength="15" value="{$cpf}"></td>
	</tr>

	<tr>
	<td><label for="email">E-mail</label></td>
	<td><input type="text" name="email" id="email" size="50" maxlength="255" value="{$email}"></td>
	</tr>

	<tr>
	<td><label for="telefone">Telefone</label></td>
	<td>(<input type="text" name="codigo_telefone" id="codigo_telefone" size="2" maxlength="2" value="{$codigo_telefone}">)
	<input type="text" name="telefone" id="telefone" size="15" maxlength="15" value="{$telefone}"></td>
	</tr>

	<tr>
	<td><label for="celular">Celular</label></td>
	<td>(<input type="text" name="codigo_celular" id="codigo_celular" size="2" maxlength="2" value="{$codigo_celular}">)
	<input type="text" name="celular" id="celular" size="15" maxlength="15" value="{$celular}"></td>
	</tr>

	<tr>
	<td><label for="endereco">Endereço</label></td>
	<td><input type="text" name="endereco" id="endereco" size="60" maxlength="100" value="{$endereco}"></td>
	</tr>

	<tr>
	<td><label for="bairro">Bairro</label></td>
	<td><input type="text" name="bairro" id="bairro" size="30" maxlength="30" value="{$bairro}"></td>
	</tr>

	<tr>
	<td><label for="municipio">Município</label></td>
	<td><input type="text" name="municipio" id="municipio" size="30" maxlength="30" value="{$municipio}"></td>
	</tr>

	<tr>
	<td><label for="cep">CEP</label></td>
	<td><input type="text" name="cep" id="cep" size="9" maxlength="9" value="{$cep}"></td>
	</tr>

	<tr>
	<td><label for="escola">Escola de formação</label></td>
	<td><input type="text" name="escola" id="escola" size="50" maxlength="70" value="{$escola}"></td>
	</tr>

	<tr>
	<td><label for="ano_formacao">Ano de formação</label></td>
	<td><input type="text" name="ano_formacao" id="ano_formacao" size="4" maxlength="4" value="{$ano_formacao}"></td>
	</tr>

	<tr>
	<td><label for="cargo">Cargo</label></td>
	<td><input type="text" name="cargo" id="cargo" size="25" maxlength="25" value="{$cargo}"></td>
	</tr>

	<tr>
	<td><label for="regiao">Região (CRESS)</label></td>
	<td><input type="text" name="regiao" id="regiao" size="3" maxlength="2" value="{$regiao}"></td>
	</tr>

	<tr>
	<td><label for="observacoes">Observações</label></td>
	<td><textarea name="observacoes" id="observacoes" rows="4" cols="60">{$observacoes}</textarea></td>
	</tr>

	{foreach from=$instituicoes item=inst}
	<tr>
	<td>Instituição vinculada</td>
	<td><a href="../instituicoes/ver_cada.php?instituicao_id={$inst.id}">{$inst.instituicao|truncate:50}</a></td>
	</tr>
	{/foreach}

	<tr class="rodape">
	<td colspan="2">
	<button type="submit" name="confirmar" class="btn">Salvar alterações</button>
	<input type="button" value="Cancelar" onclick="window.location='ver_cada.php?supervisor_id={$supervisor_id}'">
	</td>
	</tr>

	</tbody>
	</table>

	</form>

</main>

</body>
</html>
