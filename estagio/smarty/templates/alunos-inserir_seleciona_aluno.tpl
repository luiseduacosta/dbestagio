<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
<title>Inserir/atualizar aluno</title>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="author" content="Luis Acosta">
<meta name="generator" content="screem 0.12.1">
<meta name="description" content="">
<meta name="keywords" content="">
<style type="text/css">
@import url("../../estagio.css");
</style>
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

<body>

<h1>Inserir/atualizar aluno</h1>

<form name="seleciona_aluno" id="seleciona_aluno" method="post" action="verifica.php" enctype="text/html" onSubmit="return verificaRegistro();">
<h2>Digite o número de registro (DRE):</h2>
<input type="text" name="registro" id="registro" size="9" maxlength="9" onBlur="return verificaRegistro();">
<input type="hidden" name="origem" value={$origem}>
<input type="submit" name="confirma" value="Confirma" size="5">
</form>

</body>
</html>
