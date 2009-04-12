<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<link href="../../estagio.css" rel="stylesheet" type="text/css">
		<title>Ver cada professor</title>
	</head>
	<body>
		
<div align="center">
<table border="0">
<tbody>

<!--
<tr>
<td colspan="6">{$indice}</td>
</tr>
-->

<tr>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="primeiro">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="id_professor" value="{$id_professor}">
<input type="submit" name="submit" value="Primeiro">
</form>
</td>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="menos_10">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="id_professor" value="{$id_professor}">
<input type="submit" name="submit" value="- 10">
</form>
</td>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="menos_1">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="id_professor" value="{$id_professor}">
<input type="submit" name="submit" value="- 1">
</form>
</td>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="mais_1">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="id_professor" value="{$id_professor}">
<input type="submit" name="submit" value="+ 1">
</form>
</td>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="mais_10">
<input type="hidden" name="indice" value="{$indice}">
<input type="hidden" name="id_professor" value="{$id_professor}">
<input type="submit" name="submit" value="+ 10">
</form>
</td>

<td>
<form name="cabacalho" action="#" method="post">
<input type="hidden" name="botao" value="ultimo">
<input type="hidden" name="indice" value="{$indice}">
<input type="submit" name="submit" value="Último">
</form>
</td>

</tr>
</tbody>
</table>
</div>

<div align='center'>
	
	<table border='1'>
		<tbody>
		<tr>
			<th>{$professor.id}	
			</th>
			<th colspan='4'>{$professor.nome}	
			</th>
		</tr>

		{if $instituicoes != ""}
		<tr>
			<th colspan='5'>Instituições</th>
		</tr>
		
		{section name=i loop=$instituicoes}
		<tr>
			<td>{$instituicoes[i].id_instituicao}</td>
			<td colspan='4'><a href='../../instituicoes/exibir/ver_cada.php?id_instituicao={$instituicoes[i].id_instituicao}'>{$instituicoes[i].instituicao}</a></td>
		</tr>
		{/section}

		<tr>
			<th colspan='5'>Alunos</th>
		</tr>
		
		<tr>
		<th><a href='?indice={$indice}&ordem=registro'>Registro</a></th>	
		<th><a href='?indice={$indice}&ordem=nome'>Nome</a></th>
		<th><a href='?indice={$indice}&ordem=periodo'>Período</a></th>	
		<th><a href='?indice={$indice}&ordem=instituicao'>Instituição</a></th>	
		<th><a href='?indice={$indice}&ordem=area'>Área</a></th>	
		</tr>

		{section name=j loop=$alunos}
		<tr>
			<td>{$alunos[j].registro}</td>
			<td><a href="../../alunos/exibir/ver_cada.php?id_aluno={$alunos[j].id_aluno}">{$alunos[j].nome}</a></td>
			<td style='text-align:center'>{$alunos[j].periodo}</td>
			<td><a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$alunos[j].id_instituicao}">{$alunos[j].instituicao}</a></td>
			<td style='text-align:center'>{$alunos[j].area}</td>
		</tr>
		{/section}
		
		{/if}
	
	</tbody>		
	</table>
	
</div>

		
</body>
</html>
