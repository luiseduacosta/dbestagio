<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Cadastro de supervisor</title>
</head>

<body>

<div align="center">
<h3>Inserir supervisor</h3>

<form name="supervisor" action="inserir.php" method="post">

<table border="1">
<tbody>

<tr>
<td>Nome *</td>
<td><input type="text" name="nome" id="nome" size="50" maxlength="70"></td>
</tr>

<tr>
<td>Cress</td>
<td><input type="text" name="cress" id="cress" size="10" maxlength="10"></td>
</tr>

<tr>
<td>CPF</td>
<td><input type="text" name="cpf" id="cpf" size="15" maxlength="15"></td>
</tr>

<tr>
<td>E-mail</td>
<td><input type="text" name="email" id="email" size="50" maxlength="255"></td>
</tr>

<tr>
<td>Telefone</td>
<td>(<input type="text" name="codigo_telefone" id="codigo_telefone" size="2" maxlength="2" value="21">)
<input type="text" name="telefone" id="telefone" size="15" maxlength="15"></td>
</tr>

<tr>
<td>Celular</td>
<td>(<input type="text" name="codigo_celular" id="codigo_celular" size="2" maxlength="2" value="21">)
<input type="text" name="celular" id="celular" size="15" maxlength="15"></td>
</tr>

<tr>
<td>Endereço</td>
<td><input type="text" name="endereco" id="endereco" size="60" maxlength="100"></td>
</tr>

<tr>
<td>Bairro</td>
<td><input type="text" name="bairro" id="bairro" size="30" maxlength="30"></td>
</tr>

<tr>
<td>Município</td>
<td><input type="text" name="municipio" id="municipio" size="30" maxlength="30"></td>
</tr>

<tr>
<td>CEP</td>
<td><input type="text" name="cep" id="cep" size="9" maxlength="9"></td>
</tr>

<tr>
<td>Escola</td>
<td><input type="text" name="escola" id="escola" size="50" maxlength="70"></td>
</tr>

<tr>
<td>Ano de formação</td>
<td><input type="text" name="ano_formacao" id="ano_formacao" size="4" maxlength="4"></td>
</tr>

<tr>
<td>Cargo</td>
<td><input type="text" name="cargo" id="cargo" size="25" maxlength="25"></td>
</tr>

<tr>
<td>Região (CRESS)</td>
<td><input type="text" name="regiao" id="regiao" size="3" maxlength="2" value="7"></td>
</tr>

<tr>
<td>Observações</td>
<td><textarea name="observacoes" id="observacoes" rows="4" cols="60"></textarea></td>
</tr>

<tr>
<td>Instituição (opcional)</td>
<td>
<select name="instituicao_id" id="instituicao_id">
<option value="0" selected>-- Nenhuma --</option>
{html_options values=$num_instituicao selected=$instituicao_id output=$instituicao}
</select>
<br>(se escolhida, o supervisor já fica vinculado à instituição)
</td>
</tr>

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" name="confirmar" value="Salvar supervisor">
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>

</html>
