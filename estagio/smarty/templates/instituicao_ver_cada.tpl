<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Ver cada instituição</title>
{literal}
<script>
function barra() {
    var valor = document.getElementById("barra_indice").value;
    window.location="ver_cada.php?indice="+valor;
    return true;
    // alert("Hola" + valor);
}

function elimina() {
    var confirma;
    confirma=confirm("Tem certeza?");
    if(confirma==true)
	return true;
    else
	return false;
}
</script>
{/literal}
</head>

<body>

{* Barra de navegacoa superior *}
<div align="center">
<table border="0">
<tbody>

<!--
debug
<tr>
<td colspan="6">{$indice}</td>
</tr>
debug
//-->

<tr>

{if $sistema_autentica == 1}
	<td style="background-color:red">
	<form name="cabacalho" action="ver_cada.php" method="post">
	<input type="hidden" name="botao" value="inserir">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" name="inserir" value="Inserir">
	</form>
	</td>
{/if}

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="primeiro">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="Primeiro">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="menos_10">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="- 10">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="menos_1">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="- 1">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="mais_1">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="+ 1">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="mais_10">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="+ 10">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="ultimo">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="Último">
</form>
</td>

{* Excluir registro *}
{if $sistema_autentica == 1}
	<td style="background-color:red">
	<form name="cabacalho" action="ver_cada.php" method="post">
	<input type="hidden" name="botao" value="excluir">
	<input type="hidden" name="id_instituicao" value="{$id}">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="submit" name="submit" value="Excluir">
	</form>
	</td>
{/if}

</tr>
</tbody>
</table>
</div>
{* Fim da barra de navegacao superior *}


{* Instituicao *}
<div align="center">
<table border="1" width="95%">
<tbody>

{if $sistema_autentica == 1}
	<form name="modifica_instituicao" action="?id_instituicao={$id_instituicao}" method="post">
{/if}

<tr>
<td width="25%" colspan="2" class="rodape">Instituição</td>
<td colspan="4" class="rodape">
{if $modifica}
	<input type="text" name="instituicao" size="50" value="{$instituicao}">
{else}
	{$instituicao}
{/if}
</td>
</tr>

<tr>
<td colspan="2">Endereço</td>
<td colspan="4">
{if $modifica}
	<input type="text" name="endereco" size="50" value="{$endereco}">
{else}
	{$endereco}
{/if}
</td>
</tr>

<tr>
<td colspan="2">CEP</td>
<td colspan="4">
{if $modifica}
	<input type="text" name="cep" size="9" value="{$cep}">
{else}
	{$cep}
{/if}
</td>
</tr>

<tr>
<td colspan="2">Telefone</td>
<td colspan="4">
{if $modifica}
	<input type="text" name="telefone" size="35" value="{$telefone}">
{else}
	{$telefone}
{/if}
</td>
</tr>

<tr>
<td colspan="2">Fax</td>
<td colspan="4">
{if $modifica}
	<input type="text" name="fax" size="15" value="{$fax}">
{else}
	{$fax}
{/if}
</td>
</tr>

<tr>
<td colspan="2">Benefícios</td>
<td colspan="4">
{if $modifica}
	<input type="text" name="beneficios" size="50" value="{$beneficios}">
{else}
	{$beneficios}
{/if}
</td>
</tr>

<tr>
<td colspan="2">Estágio no final de semana</td>
<td colspan="4">
{if $modifica}

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


{else}

	{if $fim_de_semana eq 0} Não
	{elseif $fim_de_semana eq 1} Sim
	{elseif $fim_de_semana eq 2} Parcialmente
	{/if}

{/if}
</td>
</tr>

<tr>
<td colspan="2">Turma</td>
<td colspan="4">

{if $modifica}
	Campo calculado automaticamente
{else}
	<a href="../../alunos/exibir/alunos_instituicao.php?id_instituicao={$id}&periodo={$turma}">{$turma}</a>
{/if}

</td>
</tr>

<tr>
<td colspan="2">Área</td>
<td colspan="4">
{if $modifica}
	<select name="area" size="1">
	<option value='{$id_area}' selected>{$area}{$id_area_instituicao}</option>
	{section name=elementos loop=$matriz_areas}
		<option value='{$matriz_areas[elementos].id_area}'>{$matriz_areas[elementos].area}</option>
	{/section}
	</select>
{else}
	<a href="../../areas/exibir/instituicoes.php?id_area={$id_area}">{$area}</a>
{/if}
</td>
</tr>

<tr>
<td colspan="2">Convênio UFRJ</td>
<td colspan="4">
{if $modifica}
	<input type="text" name='convenio' id='id_convenio' size='5' value='{$convenio}'>
{else}
	{if $convenio != 0}
		<a href="http://www.pr1.ufrj.br/estagios/info.php?codEmpresa={$convenio}">{$convenio}</a>
	{else}
		{$convenio}
	{/if}
{/if}
</td>
</tr>

{if $sistema_autentica == 1}
	<tr>
	<td colspan="2">Observações</td>
	<td>
	{if $modifica}
		<textarea rows='4' cols='60' name='observacoes'>{$observacoes}</textarea>
	{else}
		{$observacoes}
	{/if}
	</td>
	</tr>
{/if}

{if $sistema_autentica == 1}
	<tr class="rodape">
	<td colspan="6" class="rodape">
	<input type="hidden" name="flag" value="{$flag}">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="hidden" name="id_instituicao" value="{$id}">
	<input type="submit" name="modifica" value="Modificar instituição">
	</td>
	</tr>
	</form>
{/if}

</tbody>
</table>
</div>
{* Fim de instituicao *}


{* Professores *}
{if !empty($inst_professores)}
	<div align="center">
	<table border="1" width="90%">
	<caption>Professores</caption>
	<tbody>
	{section name=i loop=$inst_professores}
		<tr>
		<td>{$inst_professores[i].nome}</td>
		<td style='text-align:center;'>{$inst_professores[i].periodo}</td>
		</tr>
	{/section}
	</tbody>
	</table>
	</div>
{/if}
{* Fim de professores *}


{* Superivosres *}
{if !empty($inst_supervisores)}
	<div align="center">
	<table border="1" width="90%">
	<caption>Supervisores</caption>
	<tbody>
	
	{section name=i loop=$inst_supervisores}
		<tr>
		<td width="10%" style="text-align:right">{$inst_supervisores[i].cress}</td>
		<td><a href="../../assistentes/exibir/ver_cada.php?id_supervisor={$inst_supervisores[i].id}">{$inst_supervisores[i].nome}</a></td>
		
		{if $sistema_autentica == 1}
			<td width="10%" style="text-align:center">
			<a href="../../assistentes/exibir/ver_cada.php?id_supervisor={$inst_supervisores[i].id}">Modifica</a>
			</td>
			
			<td width="10%" style="text-align:center">
			<a href="../../assistentes/cancelar/inst_supervisor.php?id_supervisor={$inst_supervisores[i].id}&id_instituicao={$id}">Excluir</a>
			</td>
		{/if}
	
		</tr>
	{/section}

	</tbody>
	</table>
	</div>
{/if}

{if $sistema_autentica == 1}
	<div align="center">
	<table border='1'>
	<tbody>
	
	<form name="supervisores" id="supervisores" action="../../instituicoes/exibir/ver_cada.php" method="post">
	<tr class="rodape">
	
	<td colspan="4" class="coluna_centralizada">
	<select name='id_supervisor' size='1'>
	<option value='0'>Selecione supervisor para inserir na instituição</option>

	{section name=i loop=$supervisores}
		<option value={$supervisores[i].id}>{$supervisores[i].nome}</option>
	{/section}

	</select>
	<input type="hidden" name="id_instituicao" value="{$id}">
	<input type="submit" name="submit" id="submit" value="Inserir supervisor">
	</td>
	
	</tr>
	</form>
	
	</tbody>
	</table>
	</div>
{/if}
{* Fim de supervisores *}

</body>

</html>