<?php
/**
 * Helper compartilhado do módulo de estagiários.
 * Lê e valida os campos do formulário (POST), devolvendo os dados prontos
 * para usar nas operações de inserção/edição.
 */

/**
 * Normaliza uma data de formulário para AAAA-MM-DD (ou '' se vazia/inválida).
 */
function estagiario_data($valor) {
    $valor = trim((string)$valor);
    if ($valor === '' || strtolower($valor) === '0000-00-00' || strtolower($valor) === 'null') {
        return '';
    }
    if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $valor, $m)) {
        return $m[3] . '-' . $m[2] . '-' . $m[1];
    }
    if (preg_match('#^(\d{4})-(\d{2})-(\d{2})#', $valor, $m)) {
        return $m[1] . '-' . $m[2] . '-' . $m[3];
    }
    return '';
}

/**
 * Lê e valida os campos do formulário de estágio (POST).
 */
function estagiario_ler_post() {
    $aluno_id = isset($_POST['aluno_id']) ? (int)$_POST['aluno_id'] : 0;
    if ($aluno_id <= 0) {
        die("Selecione o aluno.");
    }

    $periodo = isset($_POST['periodo']) ? trim($_POST['periodo']) : '';
    if ($periodo === '') {
        die("Informe o período.");
    }

    $instituicao_id = isset($_POST['instituicao_id']) ? (int)$_POST['instituicao_id'] : 0;
    if ($instituicao_id <= 0) {
        die("Selecione a instituição.");
    }

    $nivel = isset($_POST['nivel']) ? trim($_POST['nivel']) : '';
    if ($nivel === '') {
        die("Selecione o nível.");
    }

    // Data opcional.
    $tc_solicitacao = estagiario_data(isset($_POST['tc_solicitacao']) ? $_POST['tc_solicitacao'] : '');

    // Benefícios (checkboxes).
    $benetransporte  = isset($_POST['benetransporte']) ? 1 : 0;
    $benealimentacao = isset($_POST['benealimentacao']) ? 1 : 0;

    // Registrar o registro do aluno automaticamente a partir da tabela alunos.
    $registro = 0;
    $aluno = ADODB_Model::$db->GetOne(
        "SELECT registro FROM alunos WHERE id = ?",
        array($aluno_id)
    );
    if ($aluno !== false && $aluno !== null) {
        $registro = (int)$aluno;
    }

    return array(
        'registro'         => $registro,
        'aluno_id'         => $aluno_id,
        'nivel'            => $nivel,
        'tc'               => isset($_POST['tc']) ? (int)$_POST['tc'] : 0,
        'tc_solicitacao'   => ($tc_solicitacao === '') ? null : $tc_solicitacao,
        'instituicao_id'   => $instituicao_id,
        'supervisor_id'    => isset($_POST['supervisor_id']) ? (int)$_POST['supervisor_id'] : 0,
        'professor_id'     => isset($_POST['professor_id']) ? (int)$_POST['professor_id'] : 0,
        'periodo'          => $periodo,
        'nota'             => (isset($_POST['nota']) && $_POST['nota'] !== '') ? $_POST['nota'] : null,
        'ch'               => (isset($_POST['ch']) && $_POST['ch'] !== '') ? (int)$_POST['ch'] : null,
        'ajuste2020'       => isset($_POST['ajuste2020']) ? (string)(int)$_POST['ajuste2020'] : '0',
        'benetransporte'   => $benetransporte,
        'benealimentacao'  => $benealimentacao,
        'benebolsa'        => (isset($_POST['benebolsa']) && $_POST['benebolsa'] !== '') ? $_POST['benebolsa'] : null,
        'observacoes'      => isset($_POST['observacoes']) ? trim($_POST['observacoes']) : null,
    );
}
?>