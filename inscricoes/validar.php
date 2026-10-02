<?php
/**
 * Helper compartilhado do módulo de inscrições.
 * Lê e valida os campos do formulário (POST), devolvendo os dados prontos
 * para usar nas operações de inserção/edição.
 */
function inscricao_validar_post() {
    $registro = isset($_POST['registro']) ? (int)$_POST['registro'] : 0;
    $muralestagio_id = isset($_POST['muralestagio_id']) ? (int)$_POST['muralestagio_id'] : 0;
    $data  = isset($_POST['data']) ? trim($_POST['data']) : '';
    $periodo = isset($_POST['periodo']) ? trim($_POST['periodo']) : '';

    if ($registro <= 0) {
        die("Informe o registro (matrícula) do aluno.");
    }
    if ($muralestagio_id <= 0) {
        die("Selecione a oferta do mural.");
    }
    if ($data === '') {
        $data = date('Y-m-d');
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
        die("A data deve estar no formato AAAA-MM-DD (ou vazia para usar hoje).");
    }
    if ($periodo === '') {
        $periodo = defined('PERIODO_ATUAL') ? PERIODO_ATUAL : '';
    }

    return array(
        'registro'        => $registro,
        'muralestagio_id' => $muralestagio_id,
        'data'            => $data,
        'periodo'         => $periodo,
    );
}

/** Informa os períodos disponíveis (dos modelos Mural e Inscricao) para o formulário. */
function inscricao_periodos() {
    $periodos = Inscricao::periodos();
    $atuais   = Mural::periodos();
    return array_values(array_unique(array_merge($periodos, $atuais)));
}
?>