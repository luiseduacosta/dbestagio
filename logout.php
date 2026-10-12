<?php

require_once(__DIR__ . "/libphp/sessao.php");

// Encerra a sessão autenticada e apaga os cookies de identificação.
sessao_encerrar();

?>
<html>

<head>
<title>Lista lateral - Logout</title>
<link rel="stylesheet" type="text/css" href="lateral.css">
</head>

<body>

<div align="center">
<table>

<tr><td>
Sessão encerrada. <a href="login.php">Entrar novamente</a>
</td></tr>

<tr><td>
INSTITUIÇÕES
</td></tr>

<tr><td>
<a href=instituicoes/inserir.php target=_corpo>
Inserir</a>
</td></tr>

<tr><td>
<a href=instituicoes/seleciona.php target=_corpo>
Modificar</a>
</td></tr>

<tr><td>
<a href=instituicoes/listar.php target=_corpo>
Listar</a>
</td></tr>

<tr><td>
<a href=instituicoes/seleciona.php?opcao=cancela target=_corpo>
Cancelar</a>
</td></tr>

<tr><td>
<a href=imprimir/listagem.php target=_corpo>
Imprimir catálogo</a>
</td></tr>

<tr><td>
SUPERVISORES
</td></tr>

<tr><td>
<a href=supervisores/inserir/form_inserir.php target=_corpo>
Inserir</a>
</td></tr>

<tr><td>
<a href=supervisores/seleciona.php target=_corpo>
Modificar</a>
</td></tr>

<tr><td>
<a href=supervisores/buscar/busca.php target=_corpo>
Buscar</a>
</td></tr>

<tr><td>
<a href=supervisores/exibir/listar.php target=_corpo>
Listar</a>
</td></tr>

<tr><td>
<a href=supervisores/exibir/seleciona.php target=_corpo>
Ver supervisor</a>
</td></tr>

<tr><td>
<a href=supervisores/seleciona.php?opcao=cancela target=_corpo>
Cancelar</a>
</td></tr>

<tr><td>
ÁREAS
</td></tr>

<tr><td>
<a href=areas/inserir.php target=_corpo>
Inserir</a>
</td></tr>

<tr><td>
<a href=areas/seleciona.php target=_corpo>
Modificar</a>
</td></tr>

<tr><td>
<a href=areas/listar.php target=_corpo>
Listar</a>
</td></tr>

<tr><td>
<a href=areas/seleciona.php?opcao=cancela target=_corpo>
Cancela</a>
</td></tr>

</table>

</div>

<hr>

</body>

</html>