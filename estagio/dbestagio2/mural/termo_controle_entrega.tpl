<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Controle de entrega de termo de compromisso</title>
<style type="text/css">
@import url("../estagio.css");
</style>

{literal}
<script type="text/javascript">
	function periodo() {
		var periodo = document.getElementById('periodo').value;
		window.location="termo_controle_entrega.php?periodo="+periodo;
		return true;
	}
</script>
{/literal}

</head>
<body>

<div align="center">

<select name='periodo' id='periodo' size='1' onChange='return periodo();'>
	<option value=0>Selecione período</option>
	{section name=i loop=$periodos}
	<option value={$periodos[i].periodo}>{$periodos[i].periodo}</option>
	{/section}
</select>

{if $sistema_autentica == 1}
<br>
<a href="email_tc.php?periodo={$periodo}">Email</a>
<br>
{/if}

<table border="1" summary="Tabela de controle de solicitação do Termo de Compromisso">
	<caption>Controle de solicitação do termo de compromisso dos alunos que cursaram estágio no período {$periodo}</caption>
	<thead>
		<tr>
		<th>ID</th>
		<th><a href=?ordem=registro&periodo={$periodo}>DRE</a></th>
		<th><a href=?ordem=nome&periodo={$periodo}>Nome</a></th>
		<th><a href=?ordem=nivel&periodo={$periodo}>Nivel</a></th>
		<th><a href=?ordem=periodo&periodo={$periodo}>Período</a></th>
		<th><a href=?ordem=tc&periodo={$periodo}>TC</a></th>
		<th><a href=?ordem=tc_solicitacao&periodo={$periodo}>Data solicitação</a></th>
		{if $sistema_autentica == 1}
			<th><a href=?ordem=telefone&periodo={$periodo}>Telefone</a></th>
			<th><a href=?ordem=celular&periodo={$periodo}>Celular</a></th>
			<th><a href=?ordem=email&periodo={$periodo}>Email</a></th>
			<th><a href=?ordem=observacoes&periodo={$periodo}>Observações</a></th>
		{/if}
		{if $sistema_autentica == 1}
			<th><a href=?ordem=observacoes&periodo={$periodo}>Observações</a></th>
		{/if}
		</tr>
	</thead>
	<tbody>
		{assign var = "i" value = 1}
		{section name=i loop=$alunos}
		<tr>
			<td style='text-align:right;'>{$i++}</td>
			<td style='text-align:center;'>{$alunos[i].registro}</td>
			{if $alunos[i].mural}
				<td><a href="../alunos/exibir/ver_cada.php?id_aluno={$alunos[i].id_aluno}">{$alunos[i].nome}</a>&nbsp;<a href="ver-aluno.php?id_aluno={$alunos[i].registro}">[1]</a></td>
			{else}
				<td><a href="../alunos/exibir/ver_cada.php?id_aluno={$alunos[i].id_aluno}">{$alunos[i].nome}</a></td>
			{/if}
			<td style='text-align:center;'>{$alunos[i].nivel}</td>
			<td style='text-align:center;'>{$alunos[i].periodo}</td>
			<td style='text-align:center;'>{$alunos[i].tc}</td>
			<td style='text-align:center;'>{$alunos[i].tc_solicitacao|date_format:"%d-%m-%Y"}</td>
			{if $sistema_autentica == 1}
				<td style='text-align:center;'>{$alunos[i].telefone}</td>
				<td style='text-align:center;'>{$alunos[i].celular}</td>			
				<td>{$alunos[i].email}</td>
				<td>{$alunos[i].observacoes}</td>				
			{/if}
			{if $sistema_autentica == 1}
				<td>{$alunos[i].observacoes}</td>
			{/if}
		</tr>
		{/section}
	</tbody>
</table>
</div>

</body>
</html>