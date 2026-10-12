<?php

require_once(__DIR__ . "/libphp/sessao.php");

// Nome de quem está autenticado (identidade da sessão, não de cookie).
$sessao  = sessao_usuario();
$usuario = ($sessao !== null) ? $sessao['nome'] : '';

?>
<html>
<head>
<title>Menu lateral</title>
<link href="estagio.css" rel="stylesheet" type="text/css">
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
<td>Usuário <b><?php echo $usuario; ?></b> autorizado</td>
</tr>

</table>
</div>

</div>

</body>
</html>
