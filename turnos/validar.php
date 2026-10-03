<?php
/**
 * Helper compartilhado do módulo de turnos.
 * Lê e valida os campos do formulário (POST), devolvendo os dados prontos
 * para usar nas operações de inserção/edição.
 */
function turnos_ler_post() {
    $turno = isset($_POST['turno']) ? trim($_POST['turno']) : '';

    if ($turno === '') {
        die("Informe o nome do turno.");
    }

    return array(
        'turno' => $turno,
    );
}

?>
