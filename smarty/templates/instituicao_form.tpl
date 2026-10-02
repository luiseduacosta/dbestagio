<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Inserir instituição</title>
</head>
<body>

<div align="center">

<h3>Inserir nova instituição</h3>

<form name="insere_instituicao" action="inserir.php" method="post">

<table border="1">
<tbody>

<tr>
<td>Instituição *</td>
<td><input type="text" name="instituicao" size="60" maxlength="100"></td>
</tr>

<tr>
<td>Natureza</td>
<td><input type="text" name="natureza" size="30" maxlength="50"></td>
</tr>

<tr>
<td>Área</td>
<td>
<select name="area" size="1">
<option value="">-- Selecione --</option>
{section name=elementos loop=$matriz_areas}
	<option value="{$matriz_areas[elementos].id}">{$matriz_areas[elementos].area}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>CNPJ</td>
<td><input type="text" name="cnpj" size="18" maxlength="18"></td>
</tr>

<tr>
<td>E-mail</td>
<td><input type="text" name="email" size="60" maxlength="90"></td>
</tr>

<tr>
<td>Página web</td>
<td><input type="text" name="url" size="60" maxlength="100"></td>
</tr>

<tr>
<td>Endereço</td>
<td><input type="text" name="endereco" size="60" maxlength="105"></td>
</tr>

<tr>
<td>Bairro</td>
<td><input type="text" name="bairro" size="30" maxlength="30"></td>
</tr>

<tr>
<td>Município</td>
<td><input type="text" name="municipio" size="30" maxlength="30"></td>
</tr>

<tr>
<td>CEP</td>
<td><input type="text" name="cep" size="9" maxlength="9"></td>
</tr>

<tr>
<td>Telefone</td>
<td><input type="text" name="telefone" size="40" maxlength="50"></td>
</tr>

<tr>
<td>Benefícios</td>
<td><input type="text" name="beneficios" size="50" maxlength="50"></td>
</tr>

<tr>
<td>Estágio no final de semana</td>
<td>
<input type="radio" name="fim_de_semana" value="0">Não
<input type="radio" name="fim_de_semana" value="1">Sim
<input type="radio" name="fim_de_semana" value="2">Parcialmente
</td>
</tr>

<tr>
<td>Convênio</td>
<td><input type="text" name="convenio" size="8" maxlength="11"></td>
</tr>

<tr>
<td>Expira</td>
<td><input type="text" name="expira" size="10" maxlength="10"></td>
</tr>

<tr>
<td>Seguro</td>
<td>
<input type="radio" name="seguro" value="0">Instituição
<input type="radio" name="seguro" value="1">UFRJ
</td>
</tr>

<tr>
<td>Observações</td>
<td><textarea rows="4" cols="60" name="observacoes"></textarea></td>
</tr>

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" value="Salvar instituição">
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>
</html>