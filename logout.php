<?php

setcookie("usuario_nome");
setcookie("usuario_senha");
setcookie("mural_usuario","",0,"estagio/mural/administraco");
setcookie("mural_senha","",0,"estagio/mural/administraco");

?>

<html>

<head>
<title>Lista lateral - Logout</title>
<link href="lateral.css" rel="stylesheet" type="text/css">
</head>

<body>

<div align="center">
<table>

<?php

echo $usuario_nome . "<br>";

if($usuario_nome == "") {
    echo "
    <a href='logout.php'>
    <button>Usuario $usuario_nome logado</button>
    </a>
    ";
    }
else {
    echo "
    <a href='links.php'>
    <button>Usuario não logado</button>
    </a>
    ";
    }

?>

<tr><td>
INSTITUIÇÕES
</td></tr>

<tr><td>
<a href=instituicoes/inserir/formulario.php target=_corpo>
Inserir</a>
</td></tr>

<tr><td>
<a href=instituicoes/seleciona.php target=_corpo>
Modificar</a>
</td></tr>

<tr><td>
<a href=instituicoes/buscar/busca.php target=_corpo>
Buscar</a>
</td></tr>

<tr><td>
<a href=instituicoes/exibir/listar.php target=_corpo>
Listar</a>
</td></tr>

<tr><td>
<a href=instituicoes/exibir/seleciona.php target=_corpo>
Ver instituição</a>
</td></tr>

<tr><td>
<a href=instituicoes/exibir/ver_cada.php?indice=0 target=_corpo>
Ver c/instituição</a>
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
<a href=assistentes/inserir/form_inserir.php target=_corpo>
Inserir</a>
</td></tr>

<tr><td>
<a href=assistentes/seleciona.php target=_corpo>
Modificar</a>
</td></tr>

<tr><td>
<a href=assistentes/buscar/busca.php target=_corpo>
Buscar</a>
</td></tr>

<tr><td>
<a href=assistentes/exibir/ver_instituicoes.php target=_corpo>
Listar</a>
</td></tr>

<tr><td>
<a href=assistentes/exibir/seleciona.php target=_corpo>
Ver supervisor</a>
</td></tr>

<tr><td>
<a href=assistentes/seleciona.php?opcao=cancela target=_corpo>
Cancelar</a>
</td></tr>

<tr><td>
ÁREAS
</td></tr>

<tr><td>
<a href=areas/inserir/form_inserir.php target=_corpo>
Inserir</a>
</td></tr>

<tr><td>
<a href=areas/seleciona.php target=_corpo>
Modificar</a>
</td></tr>

<tr><td>
<a href=areas/exibir/listar.php target=_corpo>
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