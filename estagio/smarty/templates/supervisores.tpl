<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Tabela de Supervisores</title>

{literal}
<script type="text/javascript">
function carrega_tabela() {
	turma=document.getElementById('turma').value;
	ordem=document.getElementById('ordem').value;
	/* alert(turma); */
	window.location="listar_todos.php?turma=" + turma;
	return false;
}
</script>
{/literal}

</head>

<body>

<a href="javascript:history.back();">Voltar</a><br>

<input type=hidden name='ordem' id='ordem' value='{$ordem}'>

{if empty($id_instituicao)}
	<select name='turma' id='turma' onChange="return carrega_tabela();">
	<option value='0'>Selecione período</option>
	<option value='0'>Todos</option>
	{section name=i loop=$periodos}
	<option value='{$periodos[i]}'>{$periodos[i]}</option>
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

<div id="tabela">
<table border="1">
<caption>Tabela de Supervisores</caption>
<tbody>

<tr>
<th>Id</th>
<th><a href=?turma={$turma}&ordem=cress>Cress</a></th>
<th><a href=?turma={$turma}&ordem=nome>Supervisor</a></th>
<th><a href=?turma={$turma}&ordem=q_periodos>Períodos</a></th>
{if $sistema_autentica == 1}
	<th><a href=?turma={$turma}&ordem=email>E-mail</a></th>
	<th><a href=?turma={$turma}&ordem=celular>Celular</a></th>
	<th><a href=?turma={$turma}&ordem=telefone>Telefone</a></th>
{/if}
<th><a href=?turma={$turma}&ordem=instituicao>Instituição</a></th>
<th><a href=?turma={$turma}&ordem=turma>Turma</a></th>
<th><a href=?turma={$turma}&ordem=id_curso>Curso</a></th>
</tr>

{assign var=i value=1}
{section name=lista loop=$supervisores}
<tr>
<td class="coluna_direita">{$i++}</td>
<td class="coluna_direita">{$supervisores[lista].cress}</td>

<td>
{if $smarty.cookies.usuario_senha}
<a href="../exibir/ver_cada.php?id_supervisor={$supervisores[lista].id_supervisor}">{$supervisores[lista].nome}</a>
{else}
{$supervisores[lista].nome}
{/if}
</td>

<td class="coluna_direita">{$supervisores[lista].q_periodos}</td>

{if $sistema_autentica == 1}
	<td><a href="mailto:{$supervisores[lista].email}">{$supervisores[lista].email}</a></td>
	<td>{$supervisores[lista].celular}</td>
	<td>{$supervisores[lista].telefone}</td>
{/if}

<td>
{if $smarty.cookies.usuario_senha}
<a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$supervisores[lista].id_instituicao}">{$supervisores[lista].instituicao}</a>
{else}
{$supervisores[lista].instituicao}
{/if}
</td>

<td class="coluna_direita">
{if $smarty.cookies.usuario_senha}
<a href="alunos_supervisor.php?id_supervisor={$supervisores[lista].id_supervisor}&nome_supervisor={$supervisores[lista].nome}">{$supervisores[lista].turma}</a>
{else}
{$supervisores[lista].turma}
{/if}
</td>

<td class="coluna_direita">
{if $smarty.cookies.usuario_senha}
<a href="../../curso/ver_cada_supervisor.php?id_supervisor={$supervisores[lista].id_curso}">{$supervisores[lista].id_curso}</a>
{else}
{$supervisores[lista].id_curso}
{/if}
</td>

</tr>
{/section}

</tbody>
</table>
</div>

</body>

</html>