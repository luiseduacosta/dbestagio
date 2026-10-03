<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <link href="../../estagio.css" rel="stylesheet" type="text/css">
    <title>Atualiza Área</title>
</head>

<body>

    <form name="atualiza_area" action="atualiza.php" method="post">
        <input type="text" name="area" size="30" value="{$area}">
        <input type="hidden" name="area_id" value="{$area_id}">
        <input type="submit" name="submit" value="Confirma">
    </form>

</body>

</html>