<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Listar professores</title>
{literal}
<script type="text/javascript">
function get_turma() {
	turma=document.getElementById('turma').value;
	// ordem=document.getElementById('ordem').value;
	// alert(turma);
	window.location="?periodo=" + turma;
	return false;
}
</script>
{/literal}
</head>
<body>

<a href="javascript:history.back();">Voltar</a>

<br>

<select name='turma' id='turma' onChange='return get_turma();'>
{if $periodo}
	<option value='0'>{$periodo}</option>
{else}
	<option value='0'>Seleciona periodo</option>
{/if}

{section name='i' loop=$periodos}
<option value='{$periodos[i]}'>{$periodos[i]}</option>
{/section}
</select>

<div align="center">
<table border="1">
<caption>Professores {$periodo}</caption>
<tbody>

<tr>
<th>Id</th>
<th>Nome</th>
<th>Departamento</th>
<th>Área</th>
<th>Turno</th>
<th>Alunos</th>
<th>Imprime</th>
</tr>

{assign var="i" value=1}
{section name=i loop=$professores}
<tr>
<td>{$i++}</td>
<td><a href="ver_cada.php?id_professor={$professores[i].id_professor}">{$professores[i].nome}</a></td>
<td>{$professores[i].departamento}</td>
<td style='text-align:center'><a href="../../alunos/exibir/listar.php?seleciona_professor={$professores[i].id_professor}&seleciona_periodo={$periodo}&seleciona_turno={$professores[i].turno}&id_area={$professores[i].id_area}">{$professores[i].area}</a></td>
<td style='text-align:center'><a href="../../alunos/exibir/listar.php?seleciona_professor={$professores[i].id_professor}&seleciona_periodo={$periodo}&seleciona_turno={$professores[i].turno}&id_area={$professores[i].id_area}">{$professores[i].turno}</a></td>
<td style='text-align:center'><a href="../../alunos/exibir/listar.php?seleciona_professor={$professores[i].id_professor}&seleciona_periodo={$periodo}&seleciona_turno={$professores[i].turno}&id_area={$professores[i].id_area}">{$professores[i].q_alunos}</a></td>
<td style='text-align:center'><a href="../../imprimir/alunos_por_professor.php?id_professor={$professores[i].id_professor}&professor={$professores[i].nome}&id_area={$professores[i].id_area}&periodo={$periodo}">Pauta</a></td>
</tr>
{/section}
<tr>
<td colspan='5'>&nbsp;</td>
<td style='text-align:center'>{$total_alunos}</td>
<td>&nbsp;</td>
</tr>

</tbody>
</table>
</div>

</body>
</html>