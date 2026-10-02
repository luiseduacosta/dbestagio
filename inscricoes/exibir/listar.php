<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

// Período selecionado (filtra). Vazio = todos.
$periodo = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
if ($periodo === '') {
    $periodo = PERIODO_ATUAL;
}

// Oferta específica (quando acessado a partir do mural: listaInscritos.php).
$muralestagio_id = isset($_GET['muralestagio_id']) ? (int)$_GET['muralestagio_id'] : 0;

$periodos = inscricao_periodos();
if (!in_array($periodo, $periodos, true)) {
    $periodo = PERIODO_ATUAL;
}

$inscritos = Inscricao::listar($periodo, $muralestagio_id);

// Instituição da oferta (exibida no título quando filtrado por oferta).
$titulo_oferta = '';
if ($muralestagio_id > 0) {
    foreach ($inscritos as $ins) {
        if ($ins['muralestagio_id'] == $muralestagio_id) {
            $titulo_oferta = $ins['oferta_instituicao'];
            break;
        }
    }
}

$smarty = new Smarty_estagio;
$smarty->assign("periodo", $periodo);
$smarty->assign("periodos", $periodos);
$smarty->assign("muralestagio_id", $muralestagio_id);
$smarty->assign("titulo_oferta", $titulo_oferta);
$smarty->assign("inscritos", $inscritos);
$smarty->display("inscricoes_listar.tpl");

exit;

?>