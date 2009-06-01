<?php

// include_once("../../autentica.inc");
// echo $_SERVER['PHP_SELF'] . "<br>";

include_once("../../db.inc");
include_once("../../setup.php");

$smarty = new Smarty_estagio;

$smarty->display("supervisores-atualizar_seleciona_cadastro.tpl");

exit;

?>