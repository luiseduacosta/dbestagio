<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Supervisor</title>
</head>

<body>

<div align="center">
<h3>Modificar supervisor</h3>

<form name="atualiza_supervisor" action="atualiza.php" method="post">

<table border="1">
<tbody>

<tr>
<td colspan="2">Id: {$supervisor_id}</td>
</tr>

<tr>
<td>Nome *</td>
<td><input type="text" name="nome" size="50" maxlength="70" value="{$nome}"></td>
</tr>

<tr>
<td>Cress</td>
<td><input type="text" name="cress" size="10" maxlength="10" value="{$cress}"></td>
</tr>

<tr>
<td>CPF</td>
<td><input type="text" name="cpf" size="15" maxlength="15" value="{$cpf}"></td>
</tr>

<tr>
<td>E-mail</td>
<td><input type="text" name="email" size="50" maxlength="255" value="{$email}"></td>
</tr>

<tr>
<td>Telefone</td>
<td>(<input type="text" name="codigo_telefone" size="2" maxlength="2" value="{$codigo_telefone}">)
<input type="text" name="telefone" size="15" maxlength="15" value="{$telefone}"></td>
</tr>

<tr>
<td>Celular</td>
<td>(<input type="text" name="codigo_celular" size="2" maxlength="2" value="{$codigo_celular}">)
<input type="text" name="celular" size="15" maxlength="15" value="{$celular}"></td>
</tr>

<tr>
<td>Endereço</td>
<td><input type="text" name="endereco" size="60" maxlength="100" value="{$endereco}"></td>
</tr>

<tr>
<td>Bairro</td>
<td><input type="text" name="bairro" size="30" maxlength="30" value="{$bairro}"></td>
</tr>

<tr>
<td>Município</td>
<td><input type="text" name="municipio" size="30" maxlength="30" value="{$municipio}"></td>
</tr>

<tr>
<td>CEP</td>
<td><input type="text" name="cep" size="9" maxlength="9" value="{$cep}"></td>
</tr>

<tr>
<td>Escola</td>
<td><input type="text" name="escola" size="50" maxlength="70" value="{$escola}"></td>
</tr>

<tr>
<td>Ano de formação</td>
<td><input type="text" name="ano_formacao" size="4" maxlength="4" value="{$ano_formacao}"></td>
</tr>

<tr>
<td>Cargo</td>
<td><input type="text" name="cargo" size="25" maxlength="25" value="{$cargo}"></td>
</tr>

<tr>
<td>Região (CRESS)</td>
<td><input type="text" name="regiao" size="3" maxlength="2" value="{$regiao}"></td>
</tr>

<tr>
<td>Observações</td>
<td><textarea name="observacoes" rows="4" cols="60">{$observacoes}</textarea></td>
</tr>

{section name=elementos loop=$instituicao}
<tr>
<td>Instituição</td>
<td>{$instituicao[elementos].instituicao|truncate:50}</td>
</tr>
{/section}

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" value="Confirma" name="inserir">
</td>
</tr>

<input type="hidden" name="supervisor_id" value="{$supervisor_id}">
</tbody>
</table>

</form>

</div>

</body>

</html>
