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

</body>
</html>
