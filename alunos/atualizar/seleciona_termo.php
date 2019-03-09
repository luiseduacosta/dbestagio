<?php

// echo $_SERVER['PHP_SELF'] . "<br>";

include_once("../../setup.php");

$smarty = new Smarty_estagio;

$smarty->display("alunos-atualizar_seleciona_termo.tpl");

exit;

?>