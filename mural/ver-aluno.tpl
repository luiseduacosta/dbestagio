<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
	<title>Aluno atualiza mural</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="author" content="Luis Acosta">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
<style type="text/css">
@import url("../estagio.css");
</style>
<link rel="stylesheet" type="text/css" href="../lib/mygosumenu/1.0/example1.css" />
{literal}
<script type="text/javascript" src="../lib/mygosumenu/ie5.js"></script>
<script type="text/javascript" src="../lib/mygosumenu/1.0/DropDownMenu1.js"></script>
<script language="JavaScript" type="text/javascript">
</script>
{/literal}
</head>

<body style="direction: ltr;">

{if $sistema_autentica == 1}
	{include file="mural_menu.tpl"}
{/if}

<div align="center">
<h1>
{if $sistema_autentica == 1}
	<a href="mural-alunos_modifica.php?id_aluno={$id_aluno}&registro={$registro}&aluno={$aluno}">{$nome_aluno}</a>
{else}
	{$nome_aluno}
{/if}
</h1>
</div>

{if $aluno == 1}
	<div align="center" id="historico_estagios" style="visibility: visible">
	<table border="1">
	<caption>Histórico dos estágios cursados</caption>
	<tr>
	<th>Período</th>
	<th>Estágio</th>
	<th>Turno</th>
	<th>Instituição</th>
	<th>Supervisor</th>
	</tr>

	{* Estágios cursados *}
	{section name=item loop=$estagiarios}
	<tr>
	<td style='text-align:center;'>{$estagiarios[item].periodo}</td>
	<td style='text-align:center;'>{$estagiarios[item].nivel}</td>
	<td style='text-align:center;'>{$estagiarios[item].turno}</td>
	<td>{$estagiarios[item].instituicao}</td>
	<td>{$estagiarios[item].supervisor}</td>
	</tr>
	{/section}
	</table>

	</div>
{/if}

<div align="center" id="aluno_atualiza" style="visibility: visible">

<table border="1" width="80%">
<caption>Inscrições para seleção de estágio</caption>
<tbody>

{* Inscrições realizadas *}
{section name=i loop=$instituicoes}
<tr>

{if $sistema_autentica == 1}
	<td><a href='listaInscritos.php?id_instituicao={$instituicoes[i].id_instituicao}'>{$instituicoes[i].instituicao}</a></td>
{else}
	<td>{$instituicoes[i].instituicao}</td>
{/if}

<td style='text-align:center'>{$instituicoes[i].data|date_format:"%d-%m-%Y"}</td>
</tr>
{/section}

</tbody>
</table>

</div>

</body>
</html>