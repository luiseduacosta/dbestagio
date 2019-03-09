<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Histórico alunos</title>
<style type="text/css">
@import url("../estagio.css");
</style>
</head>
<body>

<p><a href='historico.php'>Voltar</a></p>

<div align='center'>

<table width='40%' border='1' summary="Resumo da seleção de estágio">
<caption>Resumo da seleção de estágio {$periodo}</caption>
<tbody>
	<tr><td>Continuam sem estágio</td><td style='text-align:center;'>{$sem_estagio}</td></tr>
	<tr><td>Não iniciaram estágio</td><td style='text-align:center;'>{$niveis.0}</td></tr>
	<tr><td>Iniciaram estágio I</td>  <td style='text-align:center;'>{$niveis.1}</td></tr>
	<tr><td>Iniciaram estágio II</td> <td style='text-align:center;'>{$niveis.2}</td></tr>
	<tr><td>Iniciaram estágio III</td><td style='text-align:center;'>{$niveis.3}</td></tr>
	<tr><td>Iniciaram estágio IV</td> <td style='text-align:center;'>{$niveis.4}</td></tr>
	<tr><th>TOTAL</th>                <th style='text-align:center;'>{$niveis.5}</th></tr>
</tbody>
</table>
	
<table border='1' summary="Alunos por periodo de seleção de estágio">
	<caption>Alunos seleção de estágio período {$periodo}</caption>
	<thead>
		<tr>
			<th>Id</th>
			<th><a href=?periodo={$periodo}&ordem=id_aluno>Registro</a></th>
			<th><a href=?periodo={$periodo}&ordem=nome>Nome</a></th>
			<th>Nivel</th>
		</tr>
	</thead>
	<tbody>
		{assign var = 'j' value = 1}
		{section name=i loop=$alunos}
		{if $alunos[i].situacao eq 0}
			<tr style='background-color:#e7e1ae'>
		{elseif $alunos[i].situacao eq 1}
		<tr>
		{/if}	
			<td style='text-align:right;'>{$j++}</td>
			<td style='text-align:center;'>{$alunos[i].id_aluno}</td>
			{if !$alunos[i].situacao eq 0}
				<td><a href='../alunos/exibir/ver_cada.php?id_aluno={$alunos[i].id_aluno}'>{$alunos[i].nome}</a></td>
			{else}
				<td>{$alunos[i].nome}</td>
			{/if}
			
			<td style='text-align:center;'>{$alunos[i].nivel}</td>
		</tr>
		{/section}
	</tbody>
</table>
</div>

</body>
</html>