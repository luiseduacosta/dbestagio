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

/**
 * Permissões do módulo de inscrições:
 *  - admin: acesso total;
 *  - aluno: apenas as próprias inscrições (pode inscrever, ver, editar e excluir);
 *  - professor/supervisor: sem acesso.
 */
function inscricao_exigir_permissao() {
    global $isAdmin, $isAluno;
    if (empty($isAdmin) && empty($isAluno)) {
        die("Acesso restrito: apenas administradores e alunos podem acessar as inscrições.");
    }
}

/** Id do aluno logado (tabela alunos), ou 0 quando não aplicável. */
function inscricao_aluno_id_logado() {
    global $usuario_aluno_id;
    return (int)($usuario_aluno_id ? $usuario_aluno_id : 0);
}

/** Registro (matrícula) do aluno logado, ou '' quando não aplicável. */
function inscricao_aluno_registro_logado() {
    $aluno_id = inscricao_aluno_id_logado();
    if ($aluno_id <= 0 || !ADODB_Model::$db) {
        return '';
    }
    $registro = ADODB_Model::$db->GetOne("SELECT registro FROM alunos WHERE id = ?", array($aluno_id));
    return ($registro === false || $registro === null) ? '' : (string)$registro;
}

/** True se a inscrição pertence ao aluno logado. */
function inscricao_e_do_aluno($ins) {
    $aluno_id = inscricao_aluno_id_logado();
    if ($aluno_id > 0 && (int)$ins['aluno_id'] === $aluno_id) {
        return true;
    }
    $registro = inscricao_aluno_registro_logado();
    return ($registro !== '' && (string)$ins['registro'] === $registro);
}
?>