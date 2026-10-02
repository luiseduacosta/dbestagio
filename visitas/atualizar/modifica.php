<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$v = Visita::find($id);

if ($v === null) {
    die("Visita não encontrada (id $id).");
}

$smarty = new Smarty_estagio;

$dados = array(
    'id'             => $v->getKey(),
    'data'           => $v->data,
    'instituicao_id' => (int)$v->instituicao_id,
    'professor_id'   => (int)$v->professor_id,
    'motivo'         => $v->motivo,
    'responsavel'    => $v->responsavel,
    'descricao'      => $v->descricao,
    'avaliacao'      => $v->avaliacao,
);

visitas_html_common($smarty, $dados);
$smarty->assign("acao", "atualiza.php");
$smarty->assign("titulo", "Editar visita");
$smarty->display("visitas_form.tpl");

exit;

?>