<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Seleciona instituição</title>
</head>

<body>

<p>Seleciona instituição</p>

<form name="seleciona_instituicao" action="ver_cada.php" method="post">
<select name="id_instituicao">
{html_options values=$id_instituicao output=$instituicoes|truncate:50}
</select>
<input type="submit" name="submit" value="Confirma">
</form>

</body>
</html>