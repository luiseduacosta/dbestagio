<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Alunos Supervisionados - {$nome_supervisor}</title>
  <link href="../estagio.css" rel="stylesheet" type="text/css">
</head>

<body>

<p>
<a href="ver_cada.php?supervisor_id={$supervisor_id}">Voltar para o supervisor</a> | 
<a href="listar.php">Voltar para a listagem</a>
</p>

<div align="center">
<table border="1">
<caption>Alunos supervisionados por: {$nome_supervisor}</caption>
<thead>
<tr>
<th><a href="?supervisor_id={$supervisor_id}&nome_supervisor={$nome_supervisor|escape:'url'}&ordem=periodo,%20nome">#</a></th>
<th><a href="?supervisor_id={$supervisor_id}&nome_supervisor={$nome_supervisor|escape:'url'}&ordem=registro">Registro</a></th>
<th><a href="?supervisor_id={$supervisor_id}&nome_supervisor={$nome_supervisor|escape:'url'}&ordem=nome">Nome do Aluno</a></th>
<th>E-mail</th>
<th><a href="?supervisor_id={$supervisor_id}&nome_supervisor={$nome_supervisor|escape:'url'}&ordem=periodo">Período</a></th>
<th><a href="?supervisor_id={$supervisor_id}&nome_supervisor={$nome_supervisor|escape:'url'}&ordem=instituicao">Instituição</a></th>
</tr>
</thead>
<tbody>

{foreach from=$estagiario item=e key=idx}
<tr>
<td class="coluna_direita">{$idx+1}</td>
<td class="coluna_direita">{$e.registro}</td>
<td>
<a href="../alunos/ver_cada.php?aluno_id={$e.aluno_id}">{$e.nome}</a>
</td>
<td>{if $e.email}<a href="mailto:{$e.email}">{$e.email}</a>{else}—{/if}</td>
<td class="coluna_centralizada">{$e.periodo}</td>
<td>
{if $e.instituicao_id}
<a href="../instituicoes/ver_cada.php?instituicao_id={$e.instituicao_id}">{$e.instituicao}</a>
{else}
{$e.instituicao}
{/if}
</td>
</tr>
{foreachelse}
<tr>
<td colspan="6" class="coluna_centralizada">Nenhum aluno encontrado para este supervisor.</td>
</tr>
{/foreach}

</tbody>
</table>
</div>

</body>
</html>
