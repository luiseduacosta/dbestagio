<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Supervisores inscritos no curso</title>
<style>
@import url(formulario_curso.css);
</style>
{literal}
<script language="JavaScript" type="text/javascript">

function get_turma() {
	var turma = document.getElementById('turma').value;
	window.location = 'inscricao_lista.php?turma=' + turma;
	return false;
}
</script>
{/literal}
</head>

<body>

<div align="center">
<table border="1">
<caption id="titulo">Curso para supervisores da ESS/UFRJ</caption>
<tbody>

<tr>

<th><a href=?ordem=curso_turma&palavra={$palavra}>Turma</a></th>

<th><a href="?turma={$turma}&ordem=num_inscricao&selecao={$selecao}">Ordem</a></th>

{if $autentica == 1}
	<th><a href="?turma={$turma}&ordem=cress&selecao={$selecao}">CRESS</a></th>
{/if}

<th><a href="?turma={$turma}&ordem=nome&selecao={$selecao}">Nome</a></th>

{if $autentica == 1}
	<th><a href="?turma={$turma}&ordem=email&selecao={$selecao}">E-mail</a></th>
	<th><a href="?turma={$turma}&ordem=telefone&selecao={$selecao}">Telefone</a></th>
	<th><a href="?turma={$turma}&ordem=celular&selecao={$selecao}">Celular</a></th>	
{/if}

<th><a href="?turma={$turma}&ordem=instituicao&selecao={$selecao}">Instituição</a></th>

{if $autentica == 1}
	<th><a href="?turma={$turma}&ordem=selecao&selecao={$selecao}">Seleção</a></th>
	<th>Imprime</th>
{/if}

</tr>

{assign var = "i" value = 1}
{section name=elemento loop=$matriz}
<tr>

<td class="coluna_centralizada">{$matriz[elemento].turma}</td>

<td class="coluna_centralizada">{$i++}</td>

{if $autentica == 1}
	<td style='text-align:right'>{$matriz[elemento].cress}</td>
{/if}

<td>
<a href='ver_cada_supervisor.php?id_supervisor={$matriz[elemento].id}'>{$matriz[elemento].nome}</a>
</td>

{if $autentica == 1}
	<td>{$matriz[elemento].email}</td>
	<td>({$matriz[elemento].codigo_tel}){$matriz[elemento].telefone}</td>
	<td>({$matriz[elemento].codigo_cel}){$matriz[elemento].celular}</td>
{/if}

<td>
<a href='../instituicoes/exibir/ver_cada.php?curso=sem&id_instituicao={$matriz[elemento].id_instituicao}'>{$matriz[elemento].instituicao}</a>
{if $matriz[elemento].id_estagio}
	<a href='../instituicoes/exibir/ver_cada.php?id_instituicao={$matriz[elemento].id_estagio}'>[1]</a>
{/if}
</td>

{if $autentica == 1}
	<td style='text-align:center'>{$matriz[elemento].selecao}</td>
	<td><a href="imprime_formulario.php?id_supervisor={$matriz[elemento].id}"><button>Imprime</button></a></td>
{/if}

</tr>
{/section}

</tbody>
</table>
</div>

</body>

</html>