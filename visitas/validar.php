<?php
/**
 * Helper compartilhado do módulo de visitas.
 * Lê e valida os campos do formulário (POST), devolvendo um array pronto
 * para atribuir ao modelo Visita. Usado por inserir.php e atualiza.php.
 */
function visitas_validar_post() {
    $instituicao_id = (int)(isset($_POST['instituicao_id']) ? $_POST['instituicao_id'] : 0);
    $professor_id   = (int)(isset($_POST['professor_id']) ? $_POST['professor_id'] : 0);
    $data           = isset($_POST['data']) ? trim($_POST['data']) : '';
    $motivo         = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';
    $responsavel    = isset($_POST['responsavel']) ? trim($_POST['responsavel']) : '';
    $descricao      = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
    $avaliacao      = isset($_POST['avaliacao']) ? trim($_POST['avaliacao']) : '';

    if ($instituicao_id <= 0) {
        die("Selecione uma instituição.");
    }
    if ($data === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
        die("Informe uma data válida (AAAA-MM-DD).");
    }
    if ($motivo === '') {
        die("Informe o motivo da visita.");
    }
    if ($responsavel === '') {
        die("Informe o responsável pela visita.");
    }

    // Limites de tamanho conforme a tabela.
    if (mb_strlen($motivo, 'UTF-8') > 256)    $motivo = mb_substr($motivo, 0, 256, 'UTF-8');
    if (mb_strlen($responsavel, 'UTF-8') > 50) $responsavel = mb_substr($responsavel, 0, 50, 'UTF-8');
    if (mb_strlen($avaliacao, 'UTF-8') > 50)   $avaliacao = mb_substr($avaliacao, 0, 50, 'UTF-8');

    return array(
        'instituicao_id' => $instituicao_id,
        'professor_id'   => $professor_id > 0 ? $professor_id : null,
        'data'           => $data,
        'motivo'         => $motivo,
        'responsavel'    => $responsavel,
        'descricao'      => $descricao !== '' ? $descricao : null,
        'avaliacao'      => $avaliacao,
    );
}

/** Preenche os seletores do formulário (dados comuns a add e edit). */
function visitas_html_common($smarty, $visitaDados = array()) {
    $smarty->assign("instituicoes", Instituicao::listar_todas());
    $smarty->assign("professores",  Visita::listar_professores());
    $smarty->assign("motivos",      Visita::motivos());
    $smarty->assign("avaliacoes",   Visita::avaliacoes());
    $smarty->assign("v", $visitaDados);
}

?>