<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Instituição</title>
</head>

<body>

<div align="center">

<form name="atualiza_instituicao" action="atualiza.php" method="post">

<table border="1">

<tbody>

<tr>
<td>Id instituição</td>
<td>{$id_instituicao}</td>
</tr>

<tr>
<td>Instituição</td>
<td>
<input type="text" name="nome_instituicao" size="50" value="{$nome_instituicao}">
</td>
</tr>

<tr>
<td>Endereço</td>
<td>
<input type="text" name="endereco_instituicao" size="50" value="{$endereco_instituicao}">
</td>
</tr>

<tr>
<td>CEP</td>
<td>
<input type="text" name="cep_instituicao" size="9" value="{$cep_instituicao}">
</td>
</tr>

<tr>
<td>Telefone</td>
<td>
<input type="text" name="telefone_instituicao" size="35" value="{$telefone_instituicao}">
</td>
</tr>

<tr>
<td>Fax</td>
<td>
<input type="text" name="fax_instituicao" size="15" value="{$fax_instituicao}">
</td>
</tr>

<tr>
<td>Benefícios</td>
<td>
<input type="text" name="beneficio_instituicao" size="50" value="{$beneficio_instituicao}">
</td>
</tr>

<tr>
<td>Estágio no final de semana</td>

<td>
{if $fim_de_semana == 0}
	<input type="radio" name="fim_de_semana" value="0" checked="$fim_de_semana}">Não
	<input type="radio" name="fim_de_semana" value="1">Sim
	<input type="radio" name="fim_de_semana" value="2">Parcialmente
{elseif $fim_de_semana == 1}
	<input type="radio" name="fim_de_semana" value="0">Não
	<input type="radio" name="fim_de_semana" value="1" checked="{$fim_de_semana}">Sim
	<input type="radio" name="fim_de_semana" value="2">Parcialmente
{elseif $fim_de_semana == 2}
	<input type="radio" name="fim_de_semana" value="0">Não
	<input type="radio" name="fim_de_semana" value="1">Sim
	<input type="radio" name="fim_de_semana" value="2" checked="{$fim_de_semana}">Parcialmente
{/if}
</td>

</tr>

<tr>
<td>Área</td>

<td>
<select name="area_instituicao" size="1">
<option value={$id_area_instituicao} selected>{$area_instituicao}</option>
{section name=elementos loop=$matriz_areas}
	<option value={$matriz_areas[elementos].id_area}>{$matriz_areas[elementos].area}</option>
{/section}
</select>
</td>
</tr>

<tr>
<td>
	Convênio
</td>
<td>
	<input type="text" name='convenio' id='id_convenio' size='5' value='{$convenio}'>
</td>	
</tr>

<tr>
<td>Última turma</td>
<td>{$turma}</td>
</tr>

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="hidden" name="id_instituicao" value="{$id_instituicao}">
<input type="submit" value="Confirma" name="inserir">
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>

</html>
