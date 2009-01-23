<?php

// Apago os cookies que já possam existir enviando um cookie sem valor
setcookie("mural_usuario","");
setcookie("mural_senha","");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Menu lateral</title>
<link href="mural.css" rel="stylesheet" type="text/css">
</head>

<body>

<div align="center">
<strong>
<font size="+1">UNIVERSIDADE FEDERAL DO RIO DE JANEIRO</font>
</strong>
</div>

<br>

<div align="center">
<strong>
<font size="+1">ESCOLA DE SERVIÇO SOCIAL</font>
</strong>
</div>

<br>

<div align="center">
<strong>
<font size="+1">Coordenação de estágio</font>
</strong>
</div>

<br>

<form name="login" id="login" action="login_verifica.php" method="post">
<table align="center">

<tr>
<td>Usuário</td>
<td><input type="text" name="usuario_nome" id="nome_usuario" size="15"></td>
</tr>

<tr>
<td>Senha</td>
<td><input type="password" name="usuario_senha" id="usuario_senha" size="10"></td>
</tr>

<tr>
<td colspan="2">
<p class="coluna_centralizada">
<input type="submit" name="submit" value="Confirma">
</td>
</tr>

</table>

</form>

</body>
</html>