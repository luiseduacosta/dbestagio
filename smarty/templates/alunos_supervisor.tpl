<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>Alunos</title>
  <meta content="author" name="Luis Acosta" >
<style type="text/css">
@import url("../../estagio.css");
</style>
</head>

<body>

<div align="center">
<table border="1">
<caption>Alunos supervisionados por: {$nome_supervisor}</caption>
<tbody>

<tr>
<th><a href="?supervisor_id={$supervisor_id}&nome_supervisor={$nome_supervisor}&ordem=registro">Registro</a></th>
<th><a href="?supervisor_id={$supervisor_id}&nome_supervisor={$nome_supervisor}&ordem=nome">Nome</a></th>
<th><a href="?supervisor_id={$supervisor_id}&nome_supervisor={$nome_supervisor}&ordem=periodo">Periodo</a></th>
<th><a href="?supervisor_id={$supervisor_id}&nome_supervisor={$nome_supervisor}&ordem=instituicao">Institui&ccedil;&atilde;o</a></th>
</tr>

{section name=elemento loop=$estagiario}
<tr>
<td>{$estagiario[elemento].registro}</td>
<td>
<a href="../../alunos/exibir/ver_cada.php?aluno_id={$estagiario[elemento].aluno_id}">{$estagiario[elemento].nome}</a>
</td>
<td style="text-align:right">{$estagiario[elemento].periodo}</td>
<td><a href="../../instituicoes/exibir/ver_cada.php?instituicao_id={$estagiario[elemento].instituicao_id}">{$estagiario[elemento].instituicao}</a></td>
</tr>
{/section}

</tbody>
</table>
</div>

</body>
</html>