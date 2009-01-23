<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
<title>Solicitação de termo de compromisso</title>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="author" content="Luis Acosta">
<meta name="generator" content="screem 0.12.1">
<meta name="description" content="">
<meta name="keywords" content="">
<style type="text/css">
@import url("../../estagio.css");
</style>

</head>

<body>
	
	<table border='1'>
		<thead>
			<tr>
				<th>Professor</th>
				<th>Instituição</th>
			</tr>
		</thead>
		<tbody>
			{section name=i loop=$instituicoes}
			<tr>
				<td>{$instituicoes[i].nome}</td>
				<td>{$instituicoes[i].instituicao}</td>
			</tr>
			{/section}
		</tbody>
	</table>
	
</body>
</html>