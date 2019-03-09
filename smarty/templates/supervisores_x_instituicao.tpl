<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Supervisores por instituição</title>
</head>

<body>

<p>
<a href="javascript:history.back();">Voltar</a>
</p>

<div align="center">
<table border="1">
<caption>Supervisores da instituição: <a href="ver_cada.php?id_instituicao={$id_instituicao}">{$instituicao}</a></caption>
<tbody>

<tr>
<th>Id</th>
<th><a href="?id_instituicao={$id_instituicao}&ordem=cress">Cress</a></th>
<th><a href="?id_instituicao={$id_instituicao}&ordem=supervisor">Supervisor</a></th>
<th><a href="?id_instituicao={$id_instituicao}&ordem=email">E-mail</a></th>
</tr>

{assign var='i' value=1}
{section name=elemento loop=$supervisores}
<tr>
<td style="text-align:center">{$i++}</td>
<td style="text-align:center">{$supervisores[elemento].cress}</td>
<td><a href="../../assistentes/exibir/ver_cada.php?id_supervisor={$supervisores[elemento].id_supervisor}">{$supervisores[elemento].nome}</a></td>
<td>{$supervisores[elemento].email}</td>
</tr>
{/section}

</tbody>
</table>
</div>

</body>

</html>