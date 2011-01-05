<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Ver cada institui��o</title>
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="author" content="Luis Acosta">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
<style type="text/css">
@import url("../estagio.css");
</style>
<link rel="stylesheet" type="text/css" href="../lib/mygosumenu/1.0/example1.css" />
<script type="text/javascript" src="../lib/mygosumenu/ie5.js"></script>
<script type="text/javascript" src="../lib/mygosumenu/1.0/DropDownMenu1.js"></script>
{literal}
<script>
function barra() {
    var valor = document.getElementById("barra_indice").value;
    window.location="ver_cada.php?indice="+valor;
    return true;
    // alert("Hola" + valor);
}
</script>
{/literal}
</head>

<body>

{if $sistema_autentica == 1}
	{include file="mural_menu.tpl"}
{/if}

<p><a href="javascript:history.back(1)">Voltar</a></p>

<div align="center">
{if $sistema_autentica == 1}
	<span class = "botao">
	<a href="imprime_cartaz.php?id_instituicao={$id_instituicao}">Imprimir cartaz</a>
	</span>
	
	<span class = "botao">
	<a href="http://200.20.112.2/estagio/mural/publica_google.php?id_instituicao={$id_instituicao}">Publicar no Google</a>
	</span>

	{* Se as inscricoes estao encerradas *}
	{if $data_hoje > $data_encerramento}
	    {* Se o email nao foi envidado *}
	    {if $data_fax == 0}
		<span class = "botao">
		<a href="agenda_tarefa.php?id_instituicao={$id_instituicao}">Enviar e-mail</a>
		</span>
		{else}
		<span class = "botao">
		<a href="agenda_tarefa.php?id_instituicao={$id_instituicao}">E-mail enviado</a>
		</span>
	    {/if}
	{/if}
{/if}

{if $data_inscricao == 0}
	<p style="text-align:center">Inscri��es diretamente na institui��o</p>
{else}
	{if $data_hoje > $data_inscricao}
	    <p style="text-align:center">Inscri��es encerradas</p>
	{else}
	    <p style="text-align:center">Inscri��es abertas</p>
	{/if}
{/if}

<!--
{if $data_selecao == "00000000"}
	<p style="text-align:center">Sem data de sele��o</p>
{else}
	{if $data_hoje > $data_selecao}
	    <p style="text-align:center">Sele��o j� realizada ou em processo</p>
	{/if}
{/if}
//-->
</div>

<div align="center">
<table border="0">
<tbody>

<tr>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="primeiro">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="num_instituicao" value="{$id_instituicao}">
<input type="hidden" name="id_instituicao" value="">
<input type="submit" name="submit" value="Primeiro">
</form>
</td>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="menos_10">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="mun_instituicao" value="{$id_instituicao}">
<input type="hidden" name="id_instituicao" value="">
<input type="submit" name="submit" value="- 10">
</form>
</td>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="menos_1">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="num_instituicao" value="{$id_instituicao}">
<input type="hidden" name="id_instituicao" value="">
<input type="submit" name="submit" value="- 1">
</form>
</td>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="mais_1">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="num_instituicao" value="{$id_instituicao}">
<input type="hidden" name="id_instituicao" value="">
<input type="submit" name="submit" value="+ 1">
</form>
</td>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="mais_10">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="num_instituicao" value="{$id_instituicao}">
<input type="hidden" name="id_instituicao" value="">
<input type="submit" name="submit" value="+ 10">
</form>
</td>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="ultimo">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="num_instituicao" value="{$id_instituicao}">
<input type="hidden" name="id_instituicao" value="">
<input type="submit" name="submit" value="�ltimo">
</form>
</td>

</tr>
</tbody>
</table>
</div>

{section name=item loop=$instituicao}

<div align="center">
<table border="1" width="95%">
<tbody>

{if $instituicao[item].convenio == 0}
<tr style="background-color:#fdb9b9">
{else}
<tr style="background-color:#c9f5bf">
{/if}
<td>Conv�nio</td>
<td>{$instituicao[item].convenio}</td>
</tr>

<tr>
<td width="30%">Institui��o</td>
{if $instituicao[item].id_estagio == 0}
	<td>{$instituicao[item].instituicao}</td>
{else}
	<td><a href="../instituicoes/exibir/ver_cada.php?id_instituicao={$instituicao[item].id_estagio}">{$instituicao[item].instituicao}</a></td>
{/if}
</tr>

<tr>
<td>Vagas</td>
<td>{$instituicao[item].vagas}</td>
</tr>

<tr>
<td>Beneficios</td>
<td>{$instituicao[item].beneficios}</td>
</tr>

<tr>
<td>Final de semana</td>
<td>{$instituicao[item].final_de_semana}</td>
</tr>

<tr>
<td>Carga hor�ria</td>
<td>{$instituicao[item].cargaHoraria}</td>
</tr>

<tr>
<td>Requisitos</td>
<td>{$instituicao[item].requisitos}</td>
</tr>

<tr>
<td>�rea</td>
<td>{$instituicao[item].area}</td>
</tr>

<tr>
<td>Professor</td>
<td>{$instituicao[item].professor}</td>
</tr>

<tr>
<td>Hor�rio</td>
<td>{$instituicao[item].horario}</td>
</tr>

{if $data_inscricao == 0}
	<tr>
	<td>Inscri��es na Coordena��o de Est�gio at�</td>
	<td>Inscri��es diretamente na institui��o</td>
	</tr>
{else}
	<tr>
	<td>Inscri��es na Coordena��o de Est�gio at�</td>
	<td>{$instituicao[item].dataInscricao}</td>
	</tr>
{/if}

<tr>
<td>Data e hor�rio da sele��o</td>
<td>
{$instituicao[item].dataSelecao}
Hor�rio: {$instituicao[item].horarioSelecao} hs.
</td>
</tr>

<tr>
<td>Local da sele��o</td>
<td>{$instituicao[item].localSelecao}</td>
</tr>

<tr>
<td>Forma de sele��o</td>
<td>{$instituicao[item].formaSelecao}</td>
</tr>

<tr>
<td>Contatos e/ou informa��es</td>
<td>{$instituicao[item].contato}</td>
</tr>

{if $sistema_autentica == 1}
	<tr style="background-color:#e7e1ae;">
	<td>Email</td>
	{if $instituicao[item].email == ""}
	    <td style="text-decoration:blink;">Sem e-mail</td>
	{else}
	    <td>{$instituicao[item].email}</td>
	{/if}
	</tr>

	<tr style="background-color:#e7e1ae;">
	<td>Email enviado em:</td>
	{if $data_hoje > $data_inscricao}
	    {if $instituicao[item].datafax == 0}
		<td style="background-color:red;">Email ainda nao enviado</td>		
	    {else}
    		<td>{$instituicao[item].datafax|date_format:"%d-%m-%Y"}</td>
	    {/if}
	{else}
	    <td>Inscricoes abertas</td>
	{/if}
	</tr>
{/if}

<tr>
<td>Outras informa��es</td>
<td>{$instituicao[item].outras}</td>
</tr>

<tr>
<td colspan="2" class="coluna_centralizada">
{if $sistema_autentica == 1}
	<span class="botao">
	{if $id_instituicao == ""}
	    <a href="mural-modifica.php?id_instituicao={$instituicao[item].id_instituicao}">Editar</a>
	{else}
	    <a href="mural-modifica.php?id_instituicao={$id_instituicao}">Editar</a>
	{/if}
	</span>
{/if}

{* Para a administra��o sempre est� aberta a possibilidade de realizar inscri��es fora de prazo *}
{if $sistema_autentica == 1}
		<span class="botao">
		{if $id_instituicao == ""}
		    <a href="selecionaAluno.php?instituicao={$instituicao[item].instituicao}&id_instituicao={$instituicao[item].id_instituicao}">Inscri��o para sele��o</a>
		{else}
		    <a href="selecionaAluno.php?instituicao={$instituicao[item].instituicao}&id_instituicao={$id_instituicao}">Inscri��o para sele��o</a>
		{/if}
		</span>
{else}
		{if $data_inscricao == 0}
				<span class="botao">
				<a href="#">Inscri��es diretamente na institui��o</a>
				</span>
		{else}
				{if $data_hoje <= $data_inscricao}
				    <span class="botao">
				    <a href="selecionaAluno.php?instituicao={$instituicao[item].instituicao}&id_instituicao={$instituicao[item].id_instituicao}">Inscri��o para sele��o</a>
				    </span>
				{/if}
		{/if}
{/if}

<span class="botao">
{if $id_instituicao == ""}
    <a href="listaInscritos.php?id_instituicao={$instituicao[item].id_instituicao}">Alunos inscritos</a>
{else}
    <a href="listaInscritos.php?id_instituicao={$id_instituicao}">Alunos inscritos</a>
{/if}
</span>

{if $sistema_autentica == 1}
<span class="botao">
{if $id_instituicao == ""}
    <a href="excluir.php?id_instituicao={$instituicao[item].id_instituicao}">Excluir</a>
{else}
    <a href="excluir.php?id_instituicao={$id_instituicao}">Excluir</a>
{/if}
</span>
{/if}

</td>
</tr>
{/section}

</tbody>
</table>
</div>

</body>

</html>