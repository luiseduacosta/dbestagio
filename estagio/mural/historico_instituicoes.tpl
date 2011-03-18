<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Oferta de vagas da estágio por período</title>
<style type="text/css">
@import url("../estagio.css");
</style>
</head>
<body>

<div align='center'>
<table border='1' summary='Tabela de ofertas de vagas por periodo'>
	<caption>Oferta de vagas de estágio período {$periodo} <br> Total: {$total_vagas}</caption>
	<thead>
		<tr>
			<th>Instituição</th>
			<th>Vagas</th>
		</tr>
	</thead>
	<tbody>
		{section name=i loop=$instituicoes}
		<tr>
		    <td>{$instituicoes[i].instituicao}</td>
			<td style='text-align:center;'>{$instituicoes[i].vagas}</td>		
		</tr>
		{/section}
	</tbody>
</table>
</div>

</body>
</html>