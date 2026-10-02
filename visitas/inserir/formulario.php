<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$smarty = new Smarty_estagio;

$dados_vazio = array(
    'id'              => null,
    'data'            => date('Y-m-d'),
    'instituicao_id'  => isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0,
    'professor_id'    => 0,
    'motivo'          => '',
    'responsavel'     => '',
    'descricao'       => '',
    'avaliacao'       => 'Boa',
);

visitas_html_common($smarty, $dados_vazio);
$smarty->assign("acao", "inserir.php");
$smarty->assign("titulo", "Inserir visita");
$smarty->display("visitas_form.tpl");

exit;

?>