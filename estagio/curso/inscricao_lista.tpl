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
    // alert("N�mero de inscri��o "+confirma);
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

<br>
{*
{if $selecao == 1}
<a href='?selecao=0&turma={$turma}&ordem={$ordem}'>Selecionados</a>
{elseif $selecao == 2}
<a href='?selecao=2&turma={$turma}&ordem={$ordem}'>Espera</a>
{else}
<a href='?selecao=1&turma={$turma}&ordem={$ordem}'>Todos</a>
{/if} 
*}

<a href='?selecao=1&turma={$turma}&ordem={$ordem}'>Selecionados</a>
<a href='?selecao=2&turma={$turma}&ordem={$ordem}'>Espera</a>
<a href='?selecao=0&turma={$turma}&ordem={$ordem}'>Todos</a>

{if $turma} {
if $autentica == 1}
<br>
<a href='email_curso.php?turma={$turma}'>E-mail</a>
<br>
{/if} 
{/if}

<form id="inscricaoRealizada" name="inscricaoRealizada" action="#">
<input type="hidden" name="registroInserido" id="registroInserido"
	value={$inscricaoRealizada}></form>

<h1>Clique <a
	href="http://groups.google.com/group/supervisores-da-essufrj">aqui</a>
para participar da lista de discuss&atilde;o no Google</h1>

<div align="center">
<table border="1">
	<caption id="titulo">{$turma}a. turma do curso para
	supervisores da ESS/UFRJ</caption>
	<thead>
		<tr>
			<th>Num</th>
			<th><a
				href="inscricao_lista.php?turma={$turma}&ordem=num_inscricao&selecao={$selecao}">Ordem</a></th>

			{if $autentica == 1}
			<th><a
				href="inscricao_lista.php?turma={$turma}&ordem=inscricao_anterior&selecao={$selecao}">Anterior</a></th>
			<th><a
				href="inscricao_lista.php?turma={$turma}&ordem=cress&selecao={$selecao}">CRESS</a></th>
			{/if}

			<th><a
				href="inscricao_lista.php?turma={$turma}&ordem=nome&selecao={$selecao}">Nome</a></th>

			{if $autentica == 1}
			<th><a
				href="inscricao_lista.php?turma={$turma}&ordem=email&selecao={$selecao}">E-mail</a></th>
			<th><a
				href="inscricao_lista.php?turma={$turma}&ordem=telefone&selecao={$selecao}">Telefone</a></th>
			<th><a
				href="inscricao_lista.php?turma={$turma}&ordem=celular&selecao={$selecao}">Celular</a></th>
			{/if}

			<th><a
				href="inscricao_lista.php?turma={$turma}&ordem=instituicao&selecao={$selecao}">Institui&ccedil;&atilde;o</a></th>

			{if $autentica == 1}
			<th><a
				href="inscricao_lista.php?turma={$turma}&ordem=selecao&selecao={$selecao}">Sele&ccedil;&atilde;o</a></th>
			<th>Imprime</th>
			{/if}

		</tr>
	</thead>
	<tbody>
		{assign var = "i" value = 1} {section name=elemento loop=$matriz}
		<tr>

			<td class="coluna_centralizada">{$matriz[elemento].num_inscricao}</td>

			<td class="coluna_centralizada">{$i++}</td>

			{if $autentica == 1}
			<td class="coluna_centralizada"><a href='ver_cada_supervisor.php?id_supervisor={$matriz[elemento].id_inscricao_anterior}'>{$matriz[elemento].inscricao_anterior}</a></td>

			<td style='text-align: right'>{$matriz[elemento].cress}</td>
			{/if}

			<td>{if $matriz[elemento].supervisores_id <> 0} <a
				href='ver_cada_supervisor.php?id_supervisor={$matriz[elemento].id}'>{$matriz[elemento].nome}</a>
			<a
				href='../assistentes/exibir/ver_cada.php?id_supervisor={$matriz[elemento].supervisores_id}'>[1]</a>
			{else} <a
				href='ver_cada_supervisor.php?id_supervisor={$matriz[elemento].id}'>{$matriz[elemento].nome}</a>
			{/if}</td>

			{if $autentica == 1}
			<td>{$matriz[elemento].email}</td>
			<td>({$matriz[elemento].codigo_tel}){$matriz[elemento].telefone}</td>
			<td>({$matriz[elemento].codigo_cel}){$matriz[elemento].celular}</td>
			{/if}

			<td><a
				href='../instituicoes/exibir/ver_cada.php?curso=sem&id_instituicao={$matriz[elemento].id_instituicao}'>{$matriz[elemento].instituicao}</a>
			{if $matriz[elemento].id_estagio} <a
				href='../instituicoes/exibir/ver_cada.php?id_instituicao={$matriz[elemento].id_estagio}'>[1]</a>
			{/if}</td>

			{if $autentica == 1}
			<td style='text-align: center'>{$matriz[elemento].selecao}</td>
			<td><a
				href="imprime_formulario.php?id_supervisor={$matriz[elemento].id}">
			<button>Imprime</button>
			</a></td>
			{/if}

		</tr>
		{/section}
	</tbody>
</table>
</div>

</body>

</html>
