<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Inserir/atualizar aluno</title>
<link rel="stylesheet" href="../../estagio.css">
<link rel="stylesheet" href="../../estagio-moderno.css">

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
			<p class="eyebrow">Coordenação de Estágio · Alunos</p>
			<h1>Inserir/atualizar aluno<span>Digite o número de registro (DRE)</span></h1>
		</div>
	</header>

	<section class="table-shell">
		<form name="seleciona_aluno" id="seleciona_aluno" method="post" action="inserir.php" enctype="text/html" onSubmit="return verificaRegistro();" class="periodo-form">
			<label for="registro">Registro (DRE)</label>
			<input type="text" name="registro" id="registro" size="9" maxlength="9" onBlur="return verificaRegistro();">
			<input type="hidden" name="origem" value={$origem}>
			<button type="submit" name="confirma" class="btn btn-sm">Confirma</button>
		</form>
	</section>

</main>

</body>
</html>
