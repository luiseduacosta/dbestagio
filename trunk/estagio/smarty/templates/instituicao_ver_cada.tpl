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

{if $curso} <h1>Curso supervisores</h1>
{/if}

{* Barra de navegacao superior *}
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

{* Inserir *}
{if !$curso}
	{if $sistema_autentica == 1}
		<td style="background-color:red">
		<form name="cabacalho" action="ver_cada.php" method="post">
		<input type="hidden" name="botao" value="inserir">
		<input type="hidden" name="indice" value="{$indice}">
		<input type="submit" name="inserir" value="Inserir">
		</form>
		</td>
	{/if}
{/if}

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="primeiro">
<input type="hidden" name="curso" value="{$curso}">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="Primeiro">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="menos_10">
<input type="hidden" name="curso" value="{$curso}">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="- 10">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="menos_1">
<input type="hidden" name="curso" value="{$curso}">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="- 1">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="mais_1">
<input type="hidden" name="curso" value="{$curso}">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="+ 1">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="mais_10">
<input type="hidden" name="curso" value="{$curso}">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="+ 10">
</form>
</td>

<td>
<form name="cabacalho" action="ver_cada.php" method="post">
<input type="hidden" name="botao" value="ultimo">
<input type="hidden" name="curso" value="{$curso}">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="Último">
</form>
</td>

{* Excluir registro *}
{if !$curso}
	{if $sistema_autentica == 1}
		<td style="background-color:red">
		<form name="cabacalho" action="ver_cada.php" method="post" onClick="return elimina();">
		<input type="hidden" name="botao" value="excluir">
		<input type="hidden" name="id_instituicao" value="{$id}">
		<input type="hidden" name="indice" value="{$indice}">
		<input type="submit" name="submit" value="Excluir">
		</form>
		</td>
	{/if}
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
{if $modifica}
	<td width="25%" colspan="1">Instituição</td>
	<td width="75%" colspan="2">
	<input type="text" name="instituicao" size="50" maxsize='50' value="{$instituicao}">
{else}
	<td width="25%" colspan="1" class="rodape">Instituição</td>
	<td width="75%" colspan="2" class="rodape">
	{if $id_curso_instituicao}
		<a href='ver_cada.php?curso=sem&id_instituicao={$id_curso_instituicao}'>{$instituicao}</a>
	{else}
		{$instituicao}
	{/if}
{/if}
</td>
</tr>

{if !$curso}
	<tr>
	<td width="25%" colspan="1">Página web</td>
	<td colspan="2">
	{if $modifica}
		<input type="text" name="url" size="50" maxsize="100" value="{$url}">
	{else}
		<a href="{$url}">{$url|truncate:50}</a>
	{/if}
	</td>
	</tr>
{/if}

<tr>
<td colspan="1">Endereço</td>
<td colspan="2">
{if $modifica}
	<input type="text" name="endereco" size="50" value="{$endereco}">
{else}
	{$endereco}
{/if}
</td>
</tr>

<tr>
<td colspan="1">CEP</td>
<td colspan="2">
{if $modifica}
	<input type="text" name="cep" size="9" value="{$cep}">
{else}
	{$cep}
{/if}
</td>
</tr>

<tr>
<td colspan="1">Bairro</td>
<td colspan="2">
{if $modifica}
	<input type="text" name="bairro" size="30" value="{$bairro}">
{else}
	{$bairro}
{/if}
</td>
</tr>

<tr>
<td colspan="1">Município</td>
<td colspan="2">
{if $modifica}
	<input type="text" name="municipio" size="30" value="{$municipio}">
{else}
	{$municipio}
{/if}
</td>
</tr>

<tr>
<td colspan="1">Telefone</td>
<td colspan="2">
{if $modifica}
	<input type="text" name="telefone" size="35" value="{$telefone}">
{else}
	{$telefone}
{/if}
</td>
</tr>

<tr>
<td colspan="1">Fax</td>
<td colspan="2">
{if $modifica}
	<input type="text" name="fax" size="15" value="{$fax}">
{else}
	{$fax}
{/if}
</td>
</tr>

<tr>
<td colspan="1">Benefícios</td>
<td colspan="2">
{if $modifica}
	<input type="text" name="beneficios" size="50" value="{$beneficios}">
{else}
	{$beneficios}
{/if}
</td>
</tr>

<tr>
<td colspan="1">Estágio no final de semana</td>
<td colspan="2">
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

{if !$curso}
	<tr>
	<td colspan="1">Turma</td>
	<td colspan="2">
	{if $modifica}
		Campo calculado automaticamente
	{else}
		<a href="../../alunos/exibir/alunos_instituicao.php?id_instituicao={$id}&periodo={$turma}">{$turma}</a>
	{/if}
	</td>
	</tr>
{/if}

<tr>
<td>Área</td>
<td>
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

<td>Natureza:&nbsp;
{if $modifica}
	<input type="text" name="natureza" size="15" value="{$natureza}">
{else}
	{$natureza}
{/if}
</td>
</tr>

{if !$curso}
	<tr>
	<td colspan="1">Convênio UFRJ</td>
	<td colspan="2">
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
{/if}

{if $sistema_autentica == 1}
	<tr>
	<td colspan="1">Observações</td>
	<td colspan="2">
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
	<td colspan="3" class="rodape">
	<input type="hidden" name="flag" value="{$flag}">
	<input type="hidden" name="indice" value="{$indice}">
	<input type="hidden" name="id_instituicao" value="{$id}">
	<input type="hidden" name="curso" value="{$curso}">
	<input type="submit" name="modifica" value="Modificar instituição">
	</td>
	</tr>
	</form>
{/if}

</tbody>
</table>
</div>
{* Fim de instituicao *}


{* Superivosres *}
{if !empty($inst_supervisores)}
	<div align="center">
	<table border="1" width="90%">
	<caption>Supervisores</caption>
	<tbody>
	
	{section name=i loop=$inst_supervisores}
		<tr>

		<td width="10%" style="text-align:right">{$inst_supervisores[i].cress}</td>
		<td>
			{* Por enquanto no fazer o link para os supervisores do curso *}
			{if $curso}
				{$inst_supervisores[i].nome}
			{else}	
				<a href="../../assistentes/exibir/ver_cada.php?id_supervisor={$inst_supervisores[i].id}">{$inst_supervisores[i].nome}</a>
				{if $inst_supervisores[i].id_curso_super}
					<a href='../../curso/ver_cada_supervisor.php?id_supervisor={$inst_supervisores[i].id_curso_super}'>[1]</a>
					&nbsp;
					<a href='ver_cada.php?curso=sem&id_instituicao={$inst_supervisores[i].id_curso_inst}'>[{$inst_supervisores[i].id_curso_inst}]</a>
				{/if}
			{/if}
		</td>

		{* Modificar e excluir supervisores somente para os supervisores de estagio *}		
		{if !$curso}
			{if $sistema_autentica == 1}
				<td width="10%" style="text-align:center">
				<a href="../../assistentes/exibir/ver_cada.php?id_supervisor={$inst_supervisores[i].id}">Modifica</a>
				</td>
				
				<td width="10%" style="text-align:center">
				<a href="../../assistentes/cancelar/inst_supervisor.php?id_supervisor={$inst_supervisores[i].id}&id_instituicao={$id}">Excluir</a>
				</td>
			{/if}
		{/if}
		</tr>
	{/section}

	</tbody>
	</table>
	</div>
{/if}

{* Inserir supervisor somente para os supervisores de estagio *}
{if !$curso}
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
{/if}
{* Fim de supervisores *}


{* Professores *}
{if !$curso}
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
{/if}
{* Fim de professores *}

</body>

</html>