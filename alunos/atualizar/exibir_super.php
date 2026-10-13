<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

$instituicao_id = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : (isset($_REQUEST['id_estagio']) ? (int)$_REQUEST['id_estagio'] : 0);

echo "<option value=\"0\">Seleciona</option>";

if ($instituicao_id > 0) {
    $instituicao = Instituicao::find($instituicao_id);
    if ($instituicao) {
        $supervisores = $instituicao->supervisores();
        foreach ($supervisores as $sup) {
            $supervisor_id = $sup['supervisor_id'];
            $supervisor    = $sup['nome'];
            echo "<option value=\"$supervisor_id\">$supervisor</option>";
        }
    }
}

exit;

?>