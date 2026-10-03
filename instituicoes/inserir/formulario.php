<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$smarty = new Smarty_estagio;

// Lista de áreas para o seletor do formulário.
$smarty->assign("matriz_areas", Instituicao::areasLista());
$smarty->assign("supervisores", Instituicao::supervisoresTodos());

$smarty->display("instituicao_form.tpl");

?>