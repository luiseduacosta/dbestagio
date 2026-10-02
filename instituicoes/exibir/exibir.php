<?php

// Visão (somente leitura) de uma instituição.
// Redireciona para o controlador canônico ver_cada.php (exibição + edição),
// eliminando código duplicado e o template inexistente (instituicao_exibir).
$instituicao_id = isset($_REQUEST['instituicao_id'])
    ? (int)$_REQUEST['instituicao_id']
    : (isset($_REQUEST['id_instituicao']) ? (int)$_REQUEST['id_instituicao'] : 0);

if ($instituicao_id <= 0) {
    die("Instituição inválida (id $instituicao_id).");
}

header("Location: ver_cada.php?instituicao_id=$instituicao_id");
exit;

?>