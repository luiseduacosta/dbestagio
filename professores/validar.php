<?php
/**
 * Helper compartilhado do módulo de professores.
 * Lê e valida os campos do formulário (POST), devolvendo os dados prontos
 * para usar nas operações de inserção/edição.
 */

/**
 * Normaliza uma data de formulário para AAAA-MM-DD.
 * Aceita AAAA-MM-DD, AAAA-MM-DD HH:MM:SS, dd/mm/aaaa e formato ISO.
 * Retorna '' (string vazia) para valores vazios.
 */
function professor_data($valor) {
    $valor = trim((string)$valor);
    if ($valor === '' || strtolower($valor) === '0000-00-00' || strtolower($valor) === 'null') {
        return '';
    }
    // dd/mm/aaaa -> aaaa-mm-dd
    if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $valor, $m)) {
        return $m[3] . '-' . $m[2] . '-' . $m[1];
    }
    // Já ISO (Ano-mês-dia), com validação básica.
    if (preg_match('#^(\d{4})-(\d{2})-(\d{2})#', $valor, $m)) {
        return $m[1] . '-' . $m[2] . '-' . $m[3];
    }
    return '';
}

/**
 * Lê e valida todos os campos do formulário de professor (POST).
 */
function professor_ler_post() {
    // Datas opcionais: vazias ou inválidas viram NULL no banco.
    $normalizaData = function($k) {
        $v = professor_data(isset($_POST[$k]) ? $_POST[$k] : '');
        return ($v === '') ? null : $v;
    };

    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    if ($nome === '') {
        die("O nome do professor é obrigatório.");
    }

    $email  = isset($_POST['email']) ? strtolower(trim($_POST['email'])) : '';
    $status = isset($_POST['status']) ? trim($_POST['status']) : 'ativo';
    $statusValidos = array('ativo', 'inativo', 'aposentado');
    if (!in_array($status, $statusValidos, true)) {
        $status = 'ativo';
    }

    return array(
        'nome'             => $nome,
        'cpf'              => isset($_POST['cpf']) ? trim($_POST['cpf']) : '',
        'siape'            => isset($_POST['siape']) ? trim($_POST['siape']) : '',
        'cress'            => isset($_POST['cress']) ? trim($_POST['cress']) : '',
        'regiao'           => isset($_POST['regiao']) ? trim($_POST['regiao']) : '',
        'codigo_telefone'  => isset($_POST['codigo_telefone']) ? trim($_POST['codigo_telefone']) : '21',
        'telefone'         => isset($_POST['telefone']) ? trim($_POST['telefone']) : '',
        'codigo_celular'   => isset($_POST['codigo_celular']) ? trim($_POST['codigo_celular']) : '21',
        'celular'          => isset($_POST['celular']) ? trim($_POST['celular']) : '',
        'email'            => $email,
        'curriculolattes'  => isset($_POST['curriculolattes']) ? trim($_POST['curriculolattes']) : '',
        'atualizacaolattes'=> $normalizaData('atualizacaolattes'),
        'dataingresso'     => $normalizaData('dataingresso'),
        'tipocargo'        => isset($_POST['tipocargo']) ? trim($_POST['tipocargo']) : '',
        'departamento'     => isset($_POST['departamento']) ? trim($_POST['departamento']) : '',
        'dataegresso'      => $normalizaData('dataegresso'),
        'motivoegresso'    => isset($_POST['motivoegresso']) ? trim($_POST['motivoegresso']) : '',
        'status'           => $status,
        'observacoes'      => isset($_POST['observacoes']) ? trim($_POST['observacoes']) : '',
    );
}
?>