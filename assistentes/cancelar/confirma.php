<?php

$id_supervisor = $_GET['id_supervisor'];

?>
<html>
<head>
<link href="../estagio.css" rel="stylesheet" type="text/css">
<title>Confirma cancela registro</title>
</head>

<body>

<div align="center">
<form action="cancela.php" name="cancela" method="post">
<input type="hidden" name="id_supervisor" value="<?php echo $id_supervisor; ?>">
<input type="submit" name="submit" value="Confirme">
</form>
</div>

</body>

</html>
