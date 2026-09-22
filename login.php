<?php
// Apago os cookies que ja possam existir enviando um cookie sem valor
setcookie("usuario", "");
setcookie("senha", "");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Menu lateral</title>
<link href="estagio.css" rel="stylesheet" type="text/css">
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
<font size="+1">Coordenação de estágio e extensão</font>
</strong>
</div>

<br>

<form name="login" id="login" action="verifica_login.php?opcao=capa" method="post">
<table align="center">
<tr>
<td>Email</td>
<td><input type="text" name="email" id="email" size="15"></td>
</tr>

<tr>
<td>Senha</td>
<td><input type="password" name="password" id="password" size="15"></td>
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