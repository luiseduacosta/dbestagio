<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../estagio.css" rel="stylesheet" type="text/css">
<title>Modificar Supervisor - {$nome}</title>
</head>

<body>

<a href="ver_cada.php?supervisor_id={$supervisor_id}">Voltar para o supervisor</a><br>

<div align="center">
<h3>Modificar Supervisor</h3>

<form name="atualiza_supervisor" action="atualizar.php" method="post">
<input type="hidden" name="supervisor_id" value="{$supervisor_id}">

<table border="1">
<tbody>

<tr>
<td colspan="2"><strong>ID:</strong> {$supervisor_id}</td>
</tr>

<tr>
<td>Nome *</td>
<td><input type="text" name="nome" id="nome" size="50" maxlength="70" value="{$nome}" required></td>
</tr>

<tr>
<td>CRESS</td>
<td><input type="text" name="cress" id="cress" size="10" maxlength="10" value="{$cress}"></td>
</tr>

<tr>
<td>CPF</td>
<td><input type="text" name="cpf" id="cpf" size="15" maxlength="15" value="{$cpf}"></td>
</tr>

<tr>
<td>E-mail</td>
<td><input type="text" name="email" id="email" size="50" maxlength="255" value="{$email}"></td>
</tr>

<tr>
<td>Telefone</td>
<td>(<input type="text" name="codigo_telefone" id="codigo_telefone" size="2" maxlength="2" value="{$codigo_telefone}">)
<input type="text" name="telefone" id="telefone" size="15" maxlength="15" value="{$telefone}"></td>
</tr>

<tr>
<td>Celular</td>
<td>(<input type="text" name="codigo_celular" id="codigo_celular" size="2" maxlength="2" value="{$codigo_celular}">)
<input type="text" name="celular" id="celular" size="15" maxlength="15" value="{$celular}"></td>
</tr>

<tr>
<td>Endereço</td>
<td><input type="text" name="endereco" id="endereco" size="60" maxlength="100" value="{$endereco}"></td>
</tr>

<tr>
<td>Bairro</td>
<td><input type="text" name="bairro" id="bairro" size="30" maxlength="30" value="{$bairro}"></td>
</tr>

<tr>
<td>Município</td>
<td><input type="text" name="municipio" id="municipio" size="30" maxlength="30" value="{$municipio}"></td>
</tr>

<tr>
<td>CEP</td>
<td><input type="text" name="cep" id="cep" size="9" maxlength="9" value="{$cep}"></td>
</tr>

<tr>
<td>Escola de formação</td>
<td><input type="text" name="escola" id="escola" size="50" maxlength="70" value="{$escola}"></td>
</tr>

<tr>
<td>Ano de formação</td>
<td><input type="text" name="ano_formacao" id="ano_formacao" size="4" maxlength="4" value="{$ano_formacao}"></td>
</tr>

<tr>
<td>Cargo</td>
<td><input type="text" name="cargo" id="cargo" size="25" maxlength="25" value="{$cargo}"></td>
</tr>

<tr>
<td>Região (CRESS)</td>
<td><input type="text" name="regiao" id="regiao" size="3" maxlength="2" value="{$regiao}"></td>
</tr>

<tr>
<td>Observações</td>
<td><textarea name="observacoes" id="observacoes" rows="4" cols="60">{$observacoes}</textarea></td>
</tr>

{foreach from=$instituicoes item=inst}
<tr>
<td>Instituição vinculada</td>
<td><a href="../instituicoes/ver_cada.php?instituicao_id={$inst.id}">{$inst.instituicao|truncate:50}</a></td>
</tr>
{/foreach}

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" name="confirmar" value="Salvar alterações">
<input type="button" value="Cancelar" onclick="window.location='ver_cada.php?supervisor_id={$supervisor_id}'">
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>
</html>
