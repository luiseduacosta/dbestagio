<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

// Permissões: admin (tudo) ou aluno (apenas as próprias inscrições).
inscricao_exigir_permissao();

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

// Aluno: a listagem mostra apenas as próprias inscrições.
if ($isAluno) {
    $filtro_aluno_id  = inscricao_aluno_id_logado();
    $filtro_aluno_reg = inscricao_aluno_registro_logado();
} else {
    $filtro_aluno_id  = 0;
    $filtro_aluno_reg = '';
}
$inscritos = Inscricao::listar($periodo, $muralestagio_id, $filtro_aluno_id, $filtro_aluno_reg);

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