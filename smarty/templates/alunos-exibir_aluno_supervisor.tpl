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

<p>
<a href="javascript:history.back();">Voltar</a>
</p>

<div align="center">
<table border="1">
<tbody>

<tr>
<th><a href="?ordem=aluno">Aluno</a></th>
<th><a href="?ordem=cress">Cress</a></th>
<th><a href="?ordem=supervisor">Supervisor</a></th>
</tr>

{section name=elemento loop=$alunos_supervisor}
<tr>
<td>
<a href="ver_cada.php?id_aluno={$alunos_supervisor[elemento].id_aluno}">{$alunos_supervisor[elemento].aluno}</a>
</td>
<td>{$alunos_supervisor[elemento].cress}</td>
<td>{$alunos_supervisor[elemento].supervisor}</td>
</tr>
{/section}

</tbody>
</table>
</div>

</body>
</html>