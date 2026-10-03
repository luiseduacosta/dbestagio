<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Tabela de Supervisores</title>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	turma=document.getElementById('turma').value;
	/* alert(turma); */
	window.location="listar.php?turma=" + turma;
	return false;
}
</script>
{/literal}

</head>

<body>

<a href="javascript:history.back();">Voltar</a><br>

{if empty($instituicao_id)}
	<select name='turma' id='turma' onChange="return carrega_tabela();">
	<option value='0'>Selecione período</option>
	<option value='0'>Todos</option>
	{section name=i loop=$periodos}
	<option value='{$periodos[i]}' {if $periodos[i] == $turma}selected{/if}>{$periodos[i]}</option>
	{/section}
	</select>
{/if}

{if $sistema_autentica == 1}
	<br>
	{if $turma}
	<a href='email.php?periodo={$turma}'>E-mail</a>
	<br>
	<a href='email_super_alunos.php?periodo={$turma}'>E-mail solicitação de cadastro de supervisor</a>
	{/if}
	<br>
{/if}

<table id="supervisores" class="display">
<caption>Tabela de Supervisores</caption>
<thead>
<tr>
<th>Id</th>
<th>Cress</th>
<th>Supervisor</th>
<th>Períodos</th>
{if $sistema_autentica == 1}
	<th>E-mail</th>
	<th>Celular</th>
	<th>Telefone</th>
{/if}
<th>Instituição</th>
<th>Turma</th>
<th>Curso</th>
</tr>
</thead>
<tbody>

{assign var=i value=1}
{section name=lista loop=$supervisores}
<tr>
<td class="coluna_direita">{$i++}</td>
<td class="coluna_direita">{$supervisores[lista].cress}</td>

<td>
<a href="ver_cada.php?supervisor_id={$supervisores[lista].supervisor_id}">{$supervisores[lista].nome}</a>
</td>

<td class="coluna_direita">{$supervisores[lista].q_periodos}</td>

{if $sistema_autentica == 1}
	<td><a href="mailto:{$supervisores[lista].email}">{$supervisores[lista].email}</a></td>
	<td>{$supervisores[lista].celular}</td>
	<td>{$supervisores[lista].telefone}</td>
{/if}

<td>
{if $smarty.cookies.usuario}
<a href="../../instituicoes/exibir/ver_cada.php?instituicao_id={$supervisores[lista].instituicao_id}">{$supervisores[lista].instituicao}</a>
{else}
{$supervisores[lista].instituicao}
{/if}
</td>

<td class="coluna_direita">
{if $smarty.cookies.usuario}
<a href="alunos_supervisor.php?supervisor_id={$supervisores[lista].supervisor_id}&nome_supervisor={$supervisores[lista].nome}">{$supervisores[lista].turma}</a>
{else}
{$supervisores[lista].turma}
{/if}
</td>

<td class="coluna_direita">
{if $smarty.cookies.usuario}
<a href="../../curso/ver_cada_supervisor.php?id_supervisor={$supervisores[lista].id_curso}">{$supervisores[lista].id_curso}</a>
{else}
{$supervisores[lista].id_curso}
{/if}
</td>

</tr>
{/section}

</tbody>
</table>

{literal}
<script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../../libjs/datatables/dataTables.min.js"></script>
<script>
$(document).ready(function () {
	$('#supervisores').DataTable({
		language: { url: '../../libjs/datatables/pt-BR.json' },
		pageLength: 25,
		lengthMenu: [10, 25, 50, 100],
		order: []
	});
});
</script>
{/literal}

</body>

</html>
