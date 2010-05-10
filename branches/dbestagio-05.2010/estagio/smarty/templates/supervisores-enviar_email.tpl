<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Supervisores - enviar e-mail</title>

{literal}
<script type="text/javascript" src="../../lib/jquery.js"></script>
<script>
$(document).ready(function() {
var ifChecked = "0";
$("input#main").click(function() {

	if(ifChecked == "0") {
		$("td").find("input").attr("checked","checked");
		ifChecked = "1";
		}
	else {
		$("td").find("input").attr("checked","");
		ifChecked = "0";
		}
	});
});
</script>
{/literal}

</head>

<body>

<div id="tabela">

<form name="email" id="email" method="POST" action="enviar_email.php">

<table border="1">
<caption>Selecionar supervisores para envio de e-mail</caption>
<tbody>

<tr>
<th></th>
<th><a href="?ordem=email&corpo={$corpo}&assunto={$assunto}">E-mail</a></th>
<th><a href="?ordem=nome&corpo={$corpo}&assunto={$assunto}">Supervisor</a></th>
<th><a href="?ordem=instituicao&corpo={$corpo}&assunto={$assunto}">Instituição</a></th>
<th><a href="?ordem=periodo&corpo={$corpo}&assunto={$assunto}">Período</a></th>
</tr>

<tr>
	<td>
	<input type="checkbox" id="main" name="0">
	</td>
</tr>


{section name=i loop=$supervisores}
<tr>

<td>
<input type="checkbox" name="{$supervisores[i].id_supervisor}" value="{$supervisores[i].email}">
</td>

<td>
{$supervisores[i].email|truncate:10}
</td>

<td>
<a href="exibir.php?id_supervisor={$supervisores[i].id_supervisor}">{$supervisores[i].nome}</a>
</td>

<td>
<a href="../../instituicoes/exibir/ver_cada.php?id_instituicao={$supervisores[i].id_instituicao}">{$supervisores[i].instituicao}</a>
</td>

<td style="text-align:center">
{$supervisores[i].periodo}
</td>
</tr>

{/section}

<tr>
<td colspan="4" class="coluna_centralizada">
<input type="hidden" name="assunto" id="assunto" value="{$assunto}">
<input type="hidden" name="corpo" id="corpo" value="{$corpo}">
<input type="submit" name="submit" value="Enviar e-mail para os supervisores selecionados">
</td>

</tr>

</tbody>
</table>

</form>

</div>

</body>

</html>