<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Hist�rico</title>
<style type="text/css">
@import url("../estagio.css");
</style>
</head>
<body>

<div align='center'>
<table border='1' summary='Hist�rico das sele��es de est�gio'>
	<caption>Hist�rico das sele��es de est�gio</caption>
<thead>
	<tr>
		<th>Per�odo</th>
		<th>Alunos</th>
		<th>Vagas</th>
		<th>Sem est�gio</th>
	</tr>
</thead>
<tbody>
	{section name=i loop=$historico}
	<tr>
		<td style='text-align:center'>{$historico[i].periodo}</td>
		<td style='text-align:right'><a href=historico.php?periodo={$historico[i].periodo}>{$historico[i].subtotal}</a></td>
		<td style='text-align:right'><a href=historico.php?selecao={$historico[i].periodo}>{$historico[i].vagas}</a></td>
		<td style='text-align:center'><a href=historico.php?periodo_sem_estagio={$historico[i].periodo}>{$historico[i].alunos_sem_estagio}</a></td>
	</tr>
	{/section}
</tbody>
</table>
</div>

</body>
</html>