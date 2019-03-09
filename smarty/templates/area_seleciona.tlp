<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../estagio.css" rel="stylesheet" type="text/css">
{literal}
<script language="JavaScript" type="text/javascript">
<!--
function elimina()
{
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
<title>Seleciona área</title>
</head>

<body>

{if $opcao == "cancela"}
<p>Cancela registro</p>
<form name="seleciona_area" action="cancelar/cancela.php" method="post">
{else}
<p>Modifica registro</p>
<form name="seleciona_area" action="atualizar/modifica.php" method="post">
{/if}

<select name=id_area>
{html_options values=$id_areas output=$areas}
</select>

{if $opcao == "cancela"}
<input type="submit" name="submit" value="Confirma" onClick="return elimina()">
{else}
<input type="submit" name="submit" value="Confirma">
{/if}

</form>

</body>

</html>
