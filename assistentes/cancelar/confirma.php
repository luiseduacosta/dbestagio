<?php

$supervisor_id = $_GET['supervisor_id'];

?>
<html>
<head>
<link href="../estagio.css" rel="stylesheet" type="text/css">
<title>Confirma cancela registro</title>
</head>

<body>

<div align="center">
<form action="cancela.php" name="cancela" method="post">
<input type="hidden" name="supervisor_id" value="<?php echo $supervisor_id; ?>">
<input type="submit" name="submit" value="Confirme">
</form>
</div>

</body>

</html>
