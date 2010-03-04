<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
		<title>Seleciona aluno</title>
		<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
		<meta http-equiv="Content-Script-Type" content="text/javascript">
		<meta http-equiv="Content-Style-Type" content="text/css">
		<meta name="author" content="Luis Acosta">
		<meta name="generator" content="screem 0.12.1">
		<meta name="description" content="">
		<meta name="keywords" content="">
<style type="text/css">
@import url("mural.css");
</style>
<script language="JavaScript" type="text/javascript">
function verificaRegistro() {
	var registro;
	tamanho_registro = document.selecionaAluno.registro.value.length;
	if (tamanho_registro < 9) {
		alert("O registro deve conter 9 d�gitos");
		document.selecionaAluno.registro.focus();
		return false;
		}
	for (var i=0; i<9; i++) {
		letra = document.selecionaAluno.registro.value.charAt(i);
		if (letra != "0" && letra != "1" && letra != "2" && letra != "3" && letra != "4" && letra != "5" && letra != "6" && letra != "7" && letra != "8" && letra != "9") {
			alert("Digite n�meros e n�o letras");
			return false;
			}
	}
	return true;
}
</script>
</head>

<body>

<?php
$instituicao    = isset($_REQUEST['instituicao']) ? $_REQUEST['instituicao'] : NULL;
$id_instituicao = isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL;
// echo "Id instituicao " . $id_instituicao . "Instituicao " . $instituicao . "<br>";
?>

<h1>Inscri��o para sele��o de est�gio na institui��o: <?php echo $instituicao; ?></h1>

<form name="seleciona_aluno" id="seleciona_aluno" method="post" action="verifica.php" enctype="text/html" onSubmit="return verificaRegistro();">
<p>Digite o seu n�mero de registro (DRE):</p>
<p>Aten��o! utilize o navegador <a href='http://br.mozdev.org'>Firefox</a> ou Internet Explorer, porque n�o est� funcionando com o Google Chrome</p>
<input type="text" name="registro" id="registro" size="9" maxlength="9" onBlur="return verificaRegistro();">
<input type="hidden" name="id_instituicao" value="<?php echo $id_instituicao; ?>">
<input type="submit" name="confirma" value="Confirma" size="5">
</form>

</body>
</html>
