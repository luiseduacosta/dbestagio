<<<<<<< HEAD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Seleciona</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="author" content="Luis Acosta">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
<style type="text/css">
@import url("../estagio.css");
</style>
{literal}
<script language="JavaScript" type="text/javascript">
<!--
function elimina() {
    var confirma;
    confirma=confirm("Tem certeza?");
    if(confirma==true)
		return true;
    else
		return false;
}
//-->
</script>
{/literal}
<title>Seleciona instituição</title>
</head>

<body>

{if $opcao === "cancela"}
	<p>Seleciona instituição para excluir</p>
	<form name="seleciona_instituicao" action="mural-excluir.php" method="post">
{else}
	<p>Seleciona instituição para modificar</p>
	<form name="seleciona_instituicao" action="mural-modifica.php" method="post">
{/if}

<select name="muralestagio_id">
{foreach from=$ofertas item=oferta}
<option value="{$oferta.id}">{$oferta.instituicao|truncate:50}</option>
{/foreach}
</select>

{if $opcao === "cancela"}
	<input type="submit" name="submit" value="Confirma" onClick="return elimina()">
{else}
	<input type="submit" name="submit" value="Confirma">
{/if}

</form>

</body>

</html>
=======
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Seleciona oferta de estágio</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
{literal}
<script language="JavaScript" type="text/javascript">
<!--
function elimina() {
    var confirma;
    confirma=confirm("Tem certeza?");
    if(confirma==true)
		return true;
    else
		return false;
}
//-->
</script>
{/literal}
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Mural</p>
			<h1>{if $opcao === "cancela"}Excluir oferta{else}Modificar oferta{/if}<span>Selecione a oferta de estágio desejada</span></h1>
		</div>
	</header>

	{if $opcao === "cancela"}
	<form class="periodo-form" name="seleciona_instituicao" action="mural-excluir.php" method="post">
	{else}
	<form class="periodo-form" name="seleciona_instituicao" action="mural-modifica.php" method="post">
	{/if}

		<label for="muralestagio_id">Oferta</label>
		<select name="muralestagio_id" id="muralestagio_id">
		{foreach from=$ofertas item=oferta}
		<option value="{$oferta.id}">{$oferta.instituicao|truncate:50}</option>
		{/foreach}
		</select>

		{if $opcao === "cancela"}
		<input type="submit" name="submit" value="Confirma" onClick="return elimina()">
		{else}
		<input type="submit" name="submit" value="Confirma">
		{/if}

	</form>

</main>

</body>
</html>
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa
