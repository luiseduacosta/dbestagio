<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>Alunos</title>
  <meta content="author" name="Luis Acosta" >
<style type="text/css">
@import url("../../estagio.css");
</style>
</head>

<body style="direction: ltr;">

<p>
<a href="javascript:history.back();">Voltar</a>
</p>

<!--
Tabela principal
//-->

<div align="center">
<table border="1">
  <tbody>

    <tr>
	<th>Id</th>
    <th><a href="?ordem=registro&id_instituicao={$id_instituicao}&periodo={$periodo}">Registro</a></th>
    <th><a href="?ordem=nome&id_instituicao={$id_instituicao}&periodo={$periodo}">Nome</a></th>
    <th><a href="?ordem=nivel&id_instituicao={$id_instituicao}&periodo={$periodo}">Nível</a></th>
    <th><a href="?ordem=turno&id_instituicao={$id_instituicao}&periodo={$periodo}">Turno</a></th>
	<th><a href="?ordem=periodo&id_instituicao={$id_instituicao}&periodo={$periodo}">Período</a></th>
    <th><a href="?ordem=instituicao&id_instituicao={$id_instituicao}&periodo={$periodo}">Instituição</a></th>
	<th><a href="?ordem=area&id_instituicao={$id_instituicao}&periodo={$periodo}">Área</a></th>
	<th><a href="?ordem=supervisor&id_instituicao={$id_instituicao}&periodo={$periodo}">Supervisor</a></th>
	</tr>

	{assign var="i" value=1}
    {section name=elemento loop=$alunos}
    <tr>
	  <td style="text-align:center">{$i++}</td>
      <td>{$alunos[elemento].registro}</td>
      <td><a href="ver_cada.php?id_aluno={$alunos[elemento].id}">{$alunos[elemento].nome}</a></td>
      <td style="text-align:center">{$alunos[elemento].nivel}</td>
      <td style="text-align:center">{$alunos[elemento].turno}</td>
      <td style="text-align:center">{$alunos[elemento].periodo}</td>
      <td><a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$id_instituicao}">{$alunos[elemento].instituicao}</a></td>
      <td style="text-align:center">{$alunos[elemento].area}</td>
      <td><a href="../../assistentes/exibir/ver_cada.php?id_supervisor={$alunos[elemento].id_supervisor}">{$alunos[elemento].supervisor}</a></td>
    </tr>
    {/section}

  </tbody>
</table>
</div>

</body>
</html>