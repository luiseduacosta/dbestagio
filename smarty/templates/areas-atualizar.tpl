<<<<<<< HEAD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <link href="../../estagio.css" rel="stylesheet" type="text/css">
    <title>Atualiza Área</title>
</head>

<body>

    <form name="atualiza_area" action="atualizar.php" method="post">
        <input type="text" name="area" size="30" value="{$area}">
        <input type="hidden" name="area_id" value="{$area_id}">
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
<title>Atualiza Área</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · ESS/UFRJ</p>
			<h1>Modificar área<span>Edição do cadastro da área</span></h1>
		</div>
	</header>

	<form name="atualiza_area" action="atualizar.php" method="post">

		<table class="card-table">
		<tbody>

		<tr>
		<th><label for="area">Área</label></th>
		<td>
		<input type="text" name="area" id="area" size="30" maxlength="90" value="{$area}">
		<input type="hidden" name="area_id" value="{$area_id}">
		</td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="submit" name="submit" value="Confirma">
		</td>
		</tr>

		</tbody>
		</table>

	</form>

</main>

</body>

</html>
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa
