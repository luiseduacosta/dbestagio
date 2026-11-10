<<<<<<< HEAD
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

<select name="instituicao_id">
{html_options values=$instituicao_id output=$nome_instituicao}
</select>

<input type="submit" name="submit" value="Confirma">

</form>

</body>

</html>
=======
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Seleciona instituição</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>

<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Instituições</p>
			<h1>{if $opcao == "cancela"}Seleciona instituição para excluir{else}Seleciona instituição para modificar{/if}<span>Escolha a instituição na lista abaixo</span></h1>
		</div>
	</header>

	<form class="periodo-form" name="seleciona_instituicao" action="ver_cada.php" method="post">

		<label for="instituicao_id">Instituição</label>
		<select name="instituicao_id" id="instituicao_id">
		{html_options values=$instituicao_id output=$nome_instituicao}
		</select>

		<input type="submit" name="submit" value="Confirma" class="btn">

	</form>

</main>

</body>

</html>
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa
