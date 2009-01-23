<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Lista de alunos inscritos</title>
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
</script>
{/literal}
</head>

<body style="direction: ltr;">

{if $mural_autentica == 1}
	{include file="mural_menu.tpl"}
{/if}

<h1>Lista de alunos que buscam estágio</h1>

<div align="center">
<h1>São {$totalAlunos} alunos que estão buscando estágio</h1>
</div>

<div align="center">
<table border="1">
	<tr>
		<th>ID</td>
		<th><a href='?ordem=inscrito'>TC</a></th>
		<th><a href='?ordem=registro'>DRE</a></th>
		<th><a href='?ordem=nome'>Nome</a></th>
		<th><a href='?ordem=quantidade'>Quantidade</a></th>
		<th><a href='?ordem=data_ultima'>Última data</a></th>
		{if $mural_autentica == 1}
			<th>Telefone</th>
			<th>Celular</th>
			<th>E-mail</th>
		{/if}
		{if $mural_autentica == 1}
			<th>Excluir</th>
		{/if}
	</tr>

{assign var = "i" value = 1}
{section name=i loop=$alunos}
{if $alunos[i].aluno eq 1}
<tr style="background-color:#f6ecec;">
{else}
<tr style="background-color:#add8e6;">
{/if}
<td style="text-align:right">{$i++}</td>
<td style="text-align:right;">{$alunos[i].inscrito}</td>
<td style="text-align:center">{$alunos[i].registro}</td>
<td><a href="ver-aluno.php?id_aluno={$alunos[i].registro}&aluno={$alunos[i].aluno}">{$alunos[i].nome}</a></td>
<td style="text-align:center">{$alunos[i].quantidade}</td>
<td style="text-align:center">{$alunos[i].data_ultima|date_format:"%d-%m-%Y"}</td>

{* Omito os telefones se não está cadastrado *}
{if $mural_autentica == 1}
	<td style="text-align:center">{$alunos[i].telefone}</td>
	<td style="text-align:center">{$alunos[i].celular}</td>
	<td>{$alunos[i].email}</td>
{/if}

{* Omito os dados de cpf, identidade e data de nascimento se não está cadastrado *}
{if $mural_autentica == 1}
	<td style="text-align:center"><a href="mural-excluir_aluno.php?id_aluno={$alunos[i].id}&registro={$alunos[i].registro}">Excluir</a></td>
{/if}

</tr>
{/section}
</table>
</div>

</body>
</html>
