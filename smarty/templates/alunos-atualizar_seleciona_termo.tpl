<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
<title>Solicitação de termo de compromisso</title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
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
	registro = document.getElementById("registro").value;
	tamanho_registro = registro.length;
	/* alert(tamanho_registro); */
	if (tamanho_registro < 9) {
		alert("O registro deve conter 9 dígitos");
		document.seleciona_aluno.registro.focus();
		return false;
		}
	for (var i=0; i<9; i++) {
		letra = document.getElementById("registro").value.charAt(i);
		/* alert(letra); */
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

<h1>Solicitação de termo de compromisso</h1>

<p>O termo de compromisso somente pode ser solicitado pelos alunos que estão em condições regimentais de cursar Estágio e OTP.</p>

<form name="seleciona_aluno" id="seleciona_aluno" method="post" action="atualiza_termo.php" onSubmit="return verificaRegistro();">
<h2>Digite o número de registro (DRE):</h2>
<input type="text" name="registro" id="registro" size="9" maxlength="9" onBlur="return verificaRegistro();">
<input type="submit" name="confirma" value="Confirma" size="5">
</form>

</body>
</html>
