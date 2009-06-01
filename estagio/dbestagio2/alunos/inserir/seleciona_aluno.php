<?php

include_once("../../autentica.inc");

include_once("../../db.inc");
include_once("../../setup.php");

$smarty = new Smarty_estagio;
$smarty->display("alunos-inserir_seleciona_aluno.tpl");

?>