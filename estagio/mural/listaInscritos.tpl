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

{if $sistema_autentica == 1}
	{include file="mural_menu.tpl"}
	<p>
	<a href="imprime.php?id_instituicao={$id_instituicao}">Imprimir</a>
	&nbsp;&nbsp;
	<a href='email_listaInscritos.php?id_instituicao={$id_instituicao}'>Enviar e-mail</a>
	</p>

{/if}

<p><a href="ver-mural.php">Voltar para mural</a></p>


<h1>Lista de inscritos para seleção de estágio na instituição: {$instituicao}</h1>

<div align="center">
<table>
<tr>
<th>ID</th>
<th>Nome</th>
{if $sistema_autentica == 1}
	<th>Telefone</th>
	<th>Celular</th>
	<th>Email</th>
{/if}
<th>Data</th>
{if $sistema_autentica == 1}
	<th>Excluir</th>
{/if}
</tr>

{assign var = "i" value = 1}
{section name=item loop=$inscritos}
{if $inscritos[item].aluno == 0}
<tr style="background-color:#f6ecec">
{else}
<tr style="background-color:#add8e6">
{/if}
<td style="text-align:right">{$i++}</td>
<td><a href="ver-aluno.php?id_aluno={$inscritos[item].registro}&aluno={$inscritos[item].aluno}">{$inscritos[item].nome}</a></td>
{if $sistema_autentica == 1}
	<td style='text-align:center;'>{$inscritos[item].telefone}</td>
	<td style='text-align:center;'>{$inscritos[item].celular}</td>
	<td>{$inscritos[item].email}</td>
{/if}

<td>{$inscritos[item].data}</td>

{if $sistema_autentica == 1}
	<td>
	<form name="excluirInscricao" id="excluirInscricao" method="post" action="excluir-inscricao.php" onSubmit="return confirma();">
	<input type="hidden" name="id" value="{$inscritos[item].id}">
	<input type="hidden" name="id_instituicao" value="{$id_instituicao}">
	<input type="hidden" name="instituicao" value="{$instituicao}">
	<input type="submit" name="submit" value="Excluir">
	</form>
	</td>
{/if}

</tr>
{/section}
</table>
</div>

</body>
</html>