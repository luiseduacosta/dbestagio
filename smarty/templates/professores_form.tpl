<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>{$titulo}</title>
</head>
<body>

<div align="center">
<h3>{$titulo}</h3>
</div>

<span style="font-size:85%;font-style:italic">Datas no formato AAAA-MM-DD.</span>

<form name="form_professor" action="{$acao}" method="post">

{if $e_edicao}
<input type="hidden" name="professor_id" value="{$v.id}">
{/if}

<table class="ficha">
<tbody>

<tr>
<td class="coluna_direita"><label for="nome">Nome *</label></td>
<td><input type="text" id="nome" name="nome" size="40" maxlength="200" value="{$v.nome}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="cpf">CPF</label></td>
<td><input type="text" id="cpf" name="cpf" size="15" maxlength="15" value="{$v.cpf}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="siape">SIAPE</label></td>
<td><input type="text" id="siape" name="siape" size="8" maxlength="8" value="{$v.siape}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="cress">CRESS</label></td>
<td><input type="text" id="cress" name="cress" size="10" maxlength="10" value="{$v.cress}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="regiao">Região</label></td>
<td><input type="text" id="regiao" name="regiao" size="2" maxlength="2" value="{$v.regiao}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="telefone">Telefone</label></td>
<td>
<input type="text" id="codigo_telefone" name="codigo_telefone" size="2" maxlength="2" value="{$v.codigo_telefone}">
<input type="text" id="telefone" name="telefone" size="15" maxlength="15" value="{$v.telefone}">
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="celular">Celular</label></td>
<td>
<input type="text" id="codigo_celular" name="codigo_celular" size="2" maxlength="2" value="{$v.codigo_celular}">
<input type="text" id="celular" name="celular" size="15" maxlength="15" value="{$v.celular}">
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="email">E-mail</label></td>
<td><input type="text" id="email" name="email" size="30" maxlength="255" value="{$v.email}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="curriculolattes">Currículo Lattes</label></td>
<td><input type="text" id="curriculolattes" name="curriculolattes" size="35" maxlength="50" value="{$v.curriculolattes}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="atualizacaolattes">Atualização Lattes</label></td>
<td><input type="text" id="atualizacaolattes" name="atualizacaolattes" size="10" maxlength="10" value="{$v.atualizacaolattes}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="dataingresso">Data de ingresso</label></td>
<td><input type="text" id="dataingresso" name="dataingresso" size="10" maxlength="10" value="{$v.dataingresso}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="tipocargo">Tipo de cargo</label></td>
<td><input type="text" id="tipocargo" name="tipocargo" size="20" maxlength="20" value="{$v.tipocargo}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="departamento">Departamento</label></td>
<td><input type="text" id="departamento" name="departamento" size="30" maxlength="30" value="{$v.departamento}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="dataegresso">Data de egresso</label></td>
<td><input type="text" id="dataegresso" name="dataegresso" size="10" maxlength="10" value="{$v.dataegresso}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="motivoegresso">Motivo de egresso</label></td>
<td><input type="text" id="motivoegresso" name="motivoegresso" size="50" maxlength="100" value="{$v.motivoegresso}"></td>
</tr>

<tr>
<td class="coluna_direita"><label for="status">Status *</label></td>
<td>
<select id="status" name="status" size="1">
{foreach item=s key=k from=$statuses}
<option value="{$k}" {if $k == $v.status}selected="selected"{/if}>{$s}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td class="coluna_direita"><label for="observacoes">Observações</label></td>
<td><textarea id="observacoes" name="observacoes" rows="3" cols="45">{$v.observacoes}</textarea></td>
</tr>

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" value="Salvar">
<input type="button" value="Cancelar" onclick="history.back()">
</td>
</tr>

</tbody>
</table>

</form>

</body>
</html>