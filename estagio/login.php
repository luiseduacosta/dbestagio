<?php

// Apago os cookies que ja possam existir enviando um cookie sem valor
setcookie("usuario_nome");
setcookie("usuario_senha");
setcookie("mural_usuario","",0,"/estagio/mural/administraco");
setcookie("mural_senha","",0,"/estagio/mural/administraco");

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
<td>Usuário</td>
<td><input type="text" name="usuario_nome" id="nome_usuario" size="15"></td>
</tr>

<tr>
<td>Senha</td>
<td><input type="password" name="usuario_senha" id="usuario_senha" size="15"></td>
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