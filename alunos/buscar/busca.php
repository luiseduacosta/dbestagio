<?php

include_once(__DIR__ . "/../../autentica.inc");
include_once(__DIR__ . "/../../libphp/models.php");

$smarty = new Smarty_estagio;
$smarty->display("alunos-busca.tpl");

?>