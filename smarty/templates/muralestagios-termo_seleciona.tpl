<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Solicitação de termo de compromisso</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
{literal}
<script language="JavaScript" type="text/javascript">
function verificaRegistro() {
	var registro;
	tamanho_registro = document.selecionaAluno.registro.value.length;
	if (tamanho_registro < 9) {
		alert("O registro deve conter 9 dígitos");
		document.selecionaAluno.registro.focus();
		return false;
		}
	for (var i=0; i<9; i++) {
		letra = document.selecionaAluno.registro.value.charAt(i);
		if (letra != "0" && letra != "1" && letra != "2" && letra != "3" && letra != "4" && letra != "5" && letra != "6" && letra != "7" && letra != "8" && letra != "9") {
			alert("Digite números e não letras");
			return false;
			}
	}
	return true;
}
</script>
{/literal}
</head>

<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · Mural</p>
			<h1>Solicitação de termo de compromisso<span>Informe o número de registro (DRE) do aluno</span></h1>
		</div>
	</header>

	<form action="termo_solicita.php" name="seleciona_aluno" id="seleciona_aluno" method="post" enctype="text/html" onSubmit="return verificaRegistro();">

		<table class="card-table">
		<tbody>

		<tr>
		<th><label for="registro">Número de registro (DRE)</label></th>
		<td><input type="text" name="registro" id="registro" size="9" maxlength="9" onBlur="return verificaRegistro();"></td>
		</tr>

		<tr class="rodape">
		<td colspan="2">
		<input type="submit" name="confirma" value="Confirma" size="5">
		</td>
		</tr>

		</tbody>
		</table>

	</form>

</main>

</body>
</html>
