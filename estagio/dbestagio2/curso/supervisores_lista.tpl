<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Supervisores inscritos no curso</title>
<style>
@import url(formulario_curso.css);
</style>
{literal}
<script language="JavaScript" type="text/javascript">

function confirmaInsere() {
    var confirma = document.getElementById("registroInserido").value;
    // alert("Número de inscrição "+confirma);
    if (confirma != 0) {
	alert("Inscricao realizada");
    }
}

function get_turma() {
	var turma = document.getElementById('turma').value;
	window.location = 'inscricao_lista.php?turma=' + turma;
	return false;
}
</script>
{/literal}
</head>

<body onLoad="return confirmaInsere();">

<select name='turma' id='turma' onChange='return get_turma();'>
<option value=0>Selecione turma</option>
<option value=0>Todas</option>
{section name=i loop=$turmas}
<option value={$turmas[i]}>{$turmas[i]}</option>
{/section}
</select>

<form id="inscricaoRealizada" name="inscricaoRealizada" action="#">
<input type="hidden" name="registroInserido" id="registroInserido" value={$inscricaoRealizada}>
</form>

<h1>Clique <a href="http://groups.google.com/group/supervisores-da-essufrj">aqui</a> para participar da lista de discuss&atilde;o no Google</h1>
<p>

<div align="center">
<table border="1">
<caption id="titulo">{$turma}a. turma do curso para supervisores da ESS/UFRJ</caption>
<tbody>

<tr>

<th><a href="inscricao_lista.php?turma={$turma}&ordem=num_inscricao">Ordem</a></th>

{if $autentica == 1}
	<th><a href="inscricao_lista.php?turma={$turma}&ordem=cress">CRESS</a></th>
{/if}

<th><a href="inscricao_lista.php?turma={$turma}&ordem=nome">Nome</a></th>

{if $autentica == 1}
	<th><a href="inscricao_lista.php?turma={$turma}&ordem=email">E-mail</a></th>
{/if}

<th><a href="inscricao_lista.php?turma={$turma}&ordem=instituicao">Instituicao</a></th>

{if $autentica == 1}
	<th>Imprime</th>
{/if}

</tr>

{assign var = "i" value = 1}
{section name=elemento loop=$matriz}
<tr>
<td class="coluna_centralizada">{$i++}</td>

{if $autentica == 1}
	<td style='text-align:right'>{$matriz[elemento].cress}</td>
{/if}

<td>
{if $matriz[elemento].supervisores_id}
<a href='ver_cada_supervisor.php?id_supervisor={$matriz[elemento].id}'>{$matriz[elemento].nome}</a>
<a href='../assistentes/exibir/ver_cada.php?id_supervisor={$matriz[elemento].supervisores_id}'>[1]</a>
{else}
<a href='ver_cada_supervisor.php?id_supervisor={$matriz[elemento].id}'>{$matriz[elemento].nome}</a>
{/if}
</td>

{if $autentica == 1}
	<td>{$matriz[elemento].email}</td>
{/if}

<td>
<a href=?id_instituicoa={$matriz[elemento].id_instituicao}>{$matriz[elemento].instituicao}</a>
{if $matriz[elemento].id_estagio}
<a href='../instituicoes/exibir/ver_cada.php?id_instituicao={$matriz[elemento].id_estagio}'>[1]</a>
{/if}
</td>

{if $autentica == 1}
	<td><a href="imprime_formulario.php?id_supervisor={$matriz[elemento].id}"><button>Imprime</button></a></td>
{/if}

</tr>
{/section}

</tbody>
</table>
</div>

</body>

</html>