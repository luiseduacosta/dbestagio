<?php

$usuario_nome = $_GET['usuario_nome'];
$mural_usuario = $_COOKIE['mural_usuario'];

?>
<html>
<head>
<title>Menu lateral</title>
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="Luis Acosta" content="author">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
<style type="text/css">
@import url("mural.css");
</style>
<link rel="stylesheet" type="text/css" href="../../../mygosumenu/1.0/example1.css" />
<script type="text/javascript" src="../../../mygosumenu/ie5.js"></script>
<script type="text/javascript" src="../../../mygosumenu/1.0/DropDownMenu1.js"></script>
</head>

<body>

<div align="center">

<table id="menu1" class="ddm1">
<tbody>

<!-- Inicio de instituicao //-->
<tr>
<td>
<a class="item1" href="javascript:void(0)">INSTITUIÇÕES</a>
<div class="section">
    <a class="item2" href="mural_inserir.php" target="_corpo">
    Inserir</a>
    <a class="item2" href="seleciona.php?opcao=modifcar" target="_corpo">
    Modificar</a>
    <a class="item2" href="ver_cada.php?indice=0" target="_corpo">
    Ver c/instituição</a>
    <a class="item2" href="ver-mural.php" target="_corpo">
    Listar todas</a>
    <a class="item2" href="seleciona.php?opcao=cancela" target="_corpo">
    Excluir</a>
    <a class="item2" href="../ver-mural.php" target="_top">
    Sair</a>
</div>
</td>
</tr>
<!-- Fim de instituicao //-->

<!-- Alunos
<tr>
<td>
<a class="box1" href="javascript:void(0)">ALUNOS
<img src="../mygosumenu/1.3/images/arrow1.gif" width="11" height="11">
</a>
<div class="section">
    <a class="box2" href="alunos/inserir/insere.php" target="_corpo">
    Inserir aluno novo</a><br>
    <a class="box2" href="alunos/inserir/seleciona.php" target="_corpo">
    Inserir estágio</a><br>
    <a class="box2" href="alunos/atualizar/seleciona.php" target="_corpo">
    Modificar</a><br>
    <a class="box2" href="alunos/exibir/listar.php" target="_corpo">
    Listar</a><br>
    <a class="box2" href="alunos/exibir/seleciona.php" target="_corpo">
    Ver aluno</a><br>
    <a class="box2" href="alunos/exibir/ver_cada.php?indice=0" target="_corpo">
    Ver cada aluno</a><br>
    <a class="box2" href="alunos/exibir/aluno_supervisor.php" target="_corpo">
    Aluno por supervisor</a><br>
    <a class="box2" href="alunos/cancelar/seleciona.php" target="_corpo">
    Cancela</a>
</div>
</td>
</tr>
//-->

<!-- Imprimir
<tr>
<td>
<a class="box1" href="javascript:void(0)">IMPRIMIR
<img src="../mygosumenu/1.3/images/arrow1.gif" width="11" height="11">
</a>
<div class="section">
    <a class="box2" href="imprimir/listagem.php" target="_corpo">
    Imprimir catálogo</a>
</div>
</td>
</tr>

//-->

</tbody>
</table>
</div>

<script type="text/javascript">
var ddm1 = new DropDownMenu1('menu1');
ddm1.init();
</script>
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
<td>Usuário <b><?php echo $mural_usuario; ?></b> autorizado</td>
</tr>

</table>
</div>

</div>

</body>
</html>