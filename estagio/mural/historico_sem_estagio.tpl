<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Histórico alunos sem estágio</title>
<style type="text/css">
@import url("../estagio.css");
</style>
</head>
<body>

<p><a href='historico.php'>Voltar</a></p>

<div align='center'>
<table border='1' summary="Alunos por periodo de seleção de estágio">
	<caption>Alunos sem estágio período {$periodo}</caption>
	<thead>
		<tr>
			<th>Id</th>
			<th>Registro</th>
			<th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Celular</th>
		</tr>
	</thead>
	<tbody>
		{assign var = 'j' value = 1}
		{section name=i loop=$sem_estagio}
		<tr>
			<td style='text-align:right;'>{$j++}</td>
			<td style='text-align:center;'>{$sem_estagio[i].id_aluno}</td>
			<td>{$sem_estagio[i].nome}</td>
                        <td>{$sem_estagio[i].email}</td>
                        <td>{$sem_estagio[i].telefone}</td>
                        <td>{$sem_estagio[i].celular}</td>
		</tr>
		{/section}
	</tbody>
</table>
</div>

</body>
</html>