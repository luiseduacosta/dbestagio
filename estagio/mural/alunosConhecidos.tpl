<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Lista de alunos conhecidos</title>
	<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="Luis Acosta" content="author">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
<style type="text/css">
@import url("../estagio.css");
</style>
<link rel="stylesheet" type="text/css" href="../../../mygosumenu/1.0/example1.css" />
<script type="text/javascript" src="../../../mygosumenu/ie5.js"></script>
<script type="text/javascript" src="../../../mygosumenu/1.0/DropDownMenu1.js"></script>
{literal}
<script language="JavaScript" type="text/javascript">
function confirma() {
	var confirma;
	confirma=confirm("Tem certeza?");
	if (confirma==true)
		return true;
	else
		return false;
	}
</script>
{/literal}
</head>

<body style="direction: ltr;">

{if $mural_autentica == 1}
	{include file="mural_menu.tpl"}
{/if}

<h1>Lista de alunos que buscam mudar de estágio</h1>

<div align="center">
<h1>São {$totalAlunos} alunos que estão buscando mudar de estágio</h1>
</div>

<div align="center">
<table border='1'>
<tr>
<th>ID</th>
<th><a href="?ordem=inscrito">TC</a></th>
<th><a href="?ordem=registro">Registro</a></th>
<th><a href="?ordem=nome">Nome</a></th>
{if $mural_autentica == 1}
	<th><a href="?ordem=telefone">Telefone</a></th>
	<th><a href="?ordem=celular">Celular</a></th>
	<th><a href="?ordem=email">Email</a></th>
{/if}

<th><a href="?ordem=nivel">Nivel</a></th>
<th><a href="?ordem=instituicao">Instituicao na que está estagiando</a></th>
</tr>

{assign var = "i" value = 1}
{section name=item loop=$inscritos}
{if $color == 0}
	<tr class="resaltado" id="resaltado">
	{assign var = "color" value = 1}
{else}
	<tr class="natural" id="natural">
	{assign var = "color" value = 0}
{/if}
<td style="text-align:right">{$i++}</td>
<td style="text-align:right">{$inscritos[item].inscrito}</td>
<td style="text-align:right">{$inscritos[item].registro}</td>
<td><a href="ver-aluno.php?id_aluno={$inscritos[item].registro}&aluno={$inscritos[item].flag}">{$inscritos[item].nome}</a></td>
{* Omito os telefones se não está cadastrado *}
{if $mural_autentica == 1}
	<td>{$inscritos[item].telefone}</td>
	<td>{$inscritos[item].celular}</td>
	<td>{$inscritos[item].email}</td>
{/if}

<td style="text-align:right">{$inscritos[item].nivel}</td>
<td>{$inscritos[item].instituicao}</td>
</tr>
{/section}
</table>
</div>

</body>
</html>