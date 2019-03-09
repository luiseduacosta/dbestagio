<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
<title>Atualizar dados do supervisor</title>
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

<script type="text/javascript" src="../../lib/jquery.js"></script>
<script type="text/javascript" src="../../lib/alphanumeric/jquery.alphanumeric.pack.js"></script>
<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
	$('#cress').numeric();
});
</script>

{/literal}
</head>

<body>

<h1>Atualizar dados do supervisor</h1>

<form name="seleciona_supervisor" id="seleciona_supervisor" method="post" action="../inserir/auto_cadastro.php" enctype="text/html">
<h2>Digite o número do CRESS (7a. região):</h2>
<p>Digite somente números. Não utilize barra, ponto, traço ou qualquer outro símbolo.</p>
<input type="text" name="cress" id="cress" size="9" maxlength="9" onBlur="return verificaRegistro();">
<input type="submit" name="confirma" value="Confirma" size="5">
</form>

</body>
</html>
