<?php

$usuario_nome = isset($_COOKIE['usuario_nome']) ? $_COOKIE['usuario_nome'] : NULL;

?>
<html>
<head>
<title>Menu lateral</title>
<link href="estagio.css" rel="stylesheet" type="text/css">

<script type='text/javascript' src='lib/jquery.js'></script>
<script type='text/javascript' src='lib/jquery.cookie.js'></script>
<script type='text/javascript'>
$(document).ready(function() {
	var autentica = $.cookie("usuario_nome");
	// alert("Autentica: " + autentica);
	if(autentica) {
		$("#administracao").css("display","block");
	};
	});
</script>

</head>

<body>

<div align="center">
<strong>
<font size="+2">Escola de Serviço Social</font>
<br>
<font size="+2">Coordenação de Estágio</font>
</strong>
<p>
<br>

<div align="center">
<table>

<tr>
<td>Usuário <b><?php echo $usuario_nome; ?></b> autorizado</td>
</tr>

</table>
</div>

</div>

</body>
</html>
