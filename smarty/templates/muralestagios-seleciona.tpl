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