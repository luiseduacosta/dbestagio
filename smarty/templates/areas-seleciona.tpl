<<<<<<< HEAD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
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
<form name="seleciona_area" action="../../areas/excluir.php" method="post">
{else}
<p>Modifica registro</p>
<form name="seleciona_area" action="../../areas/atualizar.php" method="post">
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
=======
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Seleciona área</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
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
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · ESS/UFRJ</p>
			<h1>{if $opcao == "cancela"}Excluir área{else}Modificar área{/if}<span>Selecione a área desejada</span></h1>
		</div>
	</header>

	{if $opcao == "cancela"}
	<form class="periodo-form" name="seleciona_area" action="excluir.php" method="post">
	{else}
	<form class="periodo-form" name="seleciona_area" action="atualizar.php" method="post">
	{/if}

		<label for="id_area">Área</label>
		<select name="id_area" id="id_area">
		{html_options values=$id_areas output=$areas}
		</select>

		{if $opcao == "cancela"}
		<input type="submit" name="submit" value="Confirma" onClick="return elimina()">
		{else}
		<input type="submit" name="submit" value="Confirma">
		{/if}

	</form>

</main>

</body>

</html>
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa
