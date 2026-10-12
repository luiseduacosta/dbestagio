<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="UTF-8">
	<title>Histórico</title>
	<link rel="stylesheet" type="text/css" href="../estagio.css">
</head>

<body>
	<caption>Histórico das seleções de estágio</caption>
	<div align="center">
		<table border="1" summary="Histórico das seleções de estágio">
			<thead>
				<tr>
					<th>Período</th>
					<th>Alunos</th>
					<th>Vagas</th>
					<th>Sem estágio</th>
				</tr>
			</thead>
			<tbody>
				{section name=i loop=$historico}
				<tr>
					<td style='text-align:center'>{$historico[i].periodo}</td>
					<td style='text-align:right'><a
							href=historico.php?periodo={$historico[i].periodo}>{$historico[i].subtotal}</a></td>
					<td style='text-align:right'><a
							href=historico.php?selecao={$historico[i].periodo}>{$historico[i].vagas}</a></td>
					<td style='text-align:center'><a
							href=historico.php?periodo_sem_estagio={$historico[i].periodo}>{$historico[i].alunos_sem_estagio}</a>
					</td>
				</tr>
				{/section}
			</tbody>
		</table>
	</div>

</body>

</html>