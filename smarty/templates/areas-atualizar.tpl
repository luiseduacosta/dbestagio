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
