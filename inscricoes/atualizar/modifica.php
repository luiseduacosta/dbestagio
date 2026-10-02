<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$ins = Inscricao::buscar($id);
if ($ins === null) {
    die("Inscrição não encontrada (id $id).");
}

$periodo = $ins['periodo'];
$periodos = inscricao_periodos();
$ofertas = Inscricao::ofertasPorPeriodo($periodo);

$v = array(
    'id'              => $ins['id'],
    'muralestagio_id' => $ins['muralestagio_id'],
    'registro'        => $ins['registro'],
    'periodo'         => $periodo,
    'data'            => $ins['data_iso'],
);

$smarty = new Smarty_estagio;
$smarty->assign("periodos", $periodos);
$smarty->assign("ofertas", $ofertas);
$smarty->assign("v", $v);
$smarty->assign("acao", "atualiza.php");
$smarty->assign("titulo", "Editar inscrição");
$smarty->assign("e_edicao", true);
$smarty->display("inscricoes_form.tpl");

exit;

?>