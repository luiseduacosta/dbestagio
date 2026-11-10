<<<<<<< HEAD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../estagio.css" rel="stylesheet" type="text/css">
<title>{$titulo}</title>
</head>
<body>

<div align="center">
<h3>{$titulo}</h3>

<form name="form_turno" action="{$acao}" method="post">

{if $v.id}
<input type="hidden" name="id" value="{$v.id}">
{/if}

<table border="1">
<tbody>

<tr>
<td>Turno *</td>
<td><input type="text" name="turno" size="30" value="{$v.turno}"></td>
</tr>

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" value="Salvar">
<input type="button" value="Cancelar" onclick="history.back()">
</td>
</tr>

</tbody>
</table>

</form>

</div>
=======
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{$titulo}</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Turnos</p>
			<h1>{$titulo}<span>Edição do cadastro do turno</span></h1>
		</div>
	</header>

	<form name="form_turno" action="{$acao}" method="post">

		{if $v.id}
		<input type="hidden" name="id" value="{$v.id}">
		{/if}

		<table class="card-table">
		<tbody>

		<tr>
		<th><label for="turno">Turno *</label></th>
		<td>
		<input type="text" name="turno" id="turno" size="30" value="{$v.turno}">
		</td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="submit" value="Salvar">
		<input type="button" value="Cancelar" onclick="history.back()">
		</td>
		</tr>

		</tbody>
		</table>

	</form>

</main>
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa

</body>
</html>
