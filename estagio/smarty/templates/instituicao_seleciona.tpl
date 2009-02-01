<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
<link href="../estagio.css" rel="stylesheet" type="text/css"> 
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

{if $opcao == "cancela"}
<h1>Seleciona instituição para excluir</h1>
<form name="seleciona_instituicao" action="exibir/ver_cada.php" method="post">
{else}
<h1>Seleciona instituição para modificar</h1>
<form name="seleciona_instituicao" action="exibir/ver_cada.php" method="post">
{/if}

<select name="id_instituicao">
{html_options values=$id_instituicao output=$nome_instituicao|truncate:50}
</select>

<input type="submit" name="submit" value="Confirma">

</form>

</body>

</html>