<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<title>Mural de ofertas de estágio</title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta name="author" content="Luis Acosta">
	<meta name="generator" content="screem 0.12.1">
	<meta name="description" content="">
	<meta name="keywords" content="">
<style type="text/css">
@import url("mural.css");
</style>
<link rel="stylesheet" type="text/css" href="../lib/mygosumenu/1.0/example1.css" />
<script type="text/javascript" src="../lib/mygosumenu/ie5.js"></script>
<script type="text/javascript" src="../lib/mygosumenu/1.0/DropDownMenu1.js"></script>

{literal}
<script language="JavaScript" type="text/javascript">
function janelaInsere() {
	var insere = document.getElementById("registroInserido").value;
	if (insere != "") {
		var texto = document.getElementById("alunoInscrito");
		texto.setAttribute("style","font-weight:bold; background-color=yellow; color: red");
		texto.style.cssText = "font-weight:bold; background-color:yellow; color:red";
		texto.innerHTML = "Aluna(o) "+insere+ " inscrita(o) em seleção de estágio";
		insere="";
	}
	return true;
}
</script>
{/literal}

</head>

<body id="corpo" onLoad="return janelaInsere();">

<form name="confirmaInscricao" id="confirmaInscricao" action="#" method="post" enctype="text/plain">
<input type="hidden" name="registroInserido" id="registroInserido" value="{$insere}">
</form>

<span id="alunoInscrito"></span>

{if $sistema_autentica == 1}
	{include file="/usr/local/htdocs/html/estagio/mural/mural_menu.tpl"}
{/if}

<h1>Mural de estágios - Turma {$periodo_atual}</h1>

<!-- {$smarty.now|date_format:"%d-%b-%Y"} -->

<p style="font-size:100%;background-color:#e7e1ae">Clique <a href="http://www.pr1.ufrj.br/estagios/busca.php">aqui</a>
e selecione o curso de Serviço Social para ver as instituições conveniadas com a UFRJ
</p>

<p>São {$totalVagas} vagas e {$totalAlunos} alunos ({$alunos_novos} novos e {$alunosVelhos} estagiarios) procurando estágio</p>

<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.ess.ufrj.br%2Festagio&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>

<div align="center">
<form name="inscricao" id="inscricao" action="#" method="post">
<table class="tab_mural" border="1">

<thead>
<tr>
<!--
<th>Id</th>
//-->
<th><a href=?ordem=instituicao>Instituiçao</a></th>
<th>Vagas</th>
<th>Inscritos</th>
<th>Benefícios</th>
<th><a href=?ordem=dataInscricao>Encerramento</a></th>
<th><a href=?ordem=dataSelecao>Seleção</a></th>
{if $sistema_autentica == 1}
	<th>Email enviado</th>
{/if}
</tr>
</thead>

{section name=item loop=$instituicao}

<tbody>
<tr>
{if $instituicao[item].convenio == 0}
	<tr style="background-color:#fdb9b9">
{else}
	<tr style="background-color:#c9f5bf">
{/if}
<!--
<td><a href="../instituicoes/exibir/ver_cada.php?id_instituicao={$instituicao[item].id_estagio}">{$instituicao[item].id_estagio}</a></td>
//-->
<td><a href="ver_cada.php?id_instituicao={$instituicao[item].id_instituicao}">{$instituicao[item].instituicao}</a></td>
<td class="coluna_centralizada">{$instituicao[item].vagas}</td>
<td class="coluna_centralizada"><a href="listaInscritos.php?id_instituicao={$instituicao[item].id_instituicao}">{$instituicao[item].quantidade_alunos}</a></td>
<td>{$instituicao[item].beneficios}</td>

{if $instituicao[item].dataInscricao == 0}
    <td style="text-align:center">
    Sem data
    </td>
{else}
    <td style="text-align:center">
    {$instituicao[item].dataInscricao}
    </td>
{/if}

{if $instituicao[item].dataSelecao == 0}
    <td style="text-align:center">
    Sem data
    </td>
{else}
    <td style="text-align:center">
    {$instituicao[item].dataSelecao} Horário: {$instituicao[item].horarioSelecao}
    </td>
{/if}

{if $sistema_autentica == 1}
	<td style="text-align:center">{$instituicao[item].datafax}</td>
{/if}
</tr>
</tbody>

{/section}

</table>
</form>
</div>

</body>
</html>