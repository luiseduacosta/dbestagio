<?php

include_once("../../setup.php");
require_once("../../libphp/models.php");

$instituicao_id = (int)(isset($_GET['instituicao_id']) ? $_GET['instituicao_id'] : 0);

if ($instituicao_id <= 0) {
    die("Instituição inválida (id $instituicao_id).");
}

$db = ADODB_Model::$db;

// Supervisores vinculados à instituição.
$rs = $db->Execute(
    "SELECT s.id AS supervisor_id, s.cress, s.nome, s.email
     FROM supervisores AS s
     INNER JOIN inst_super AS j ON s.id = j.supervisor_id
     WHERE j.instituicao_id = ?
     ORDER BY s.nome",
    array($instituicao_id)
);
if ($rs === false) {
    error_log("Erro ao consultar supervisores: " . $db->ErrorMsg());
    die("Não foi possível consultar as tabelas supervisores e inst_super.");
}

$supervisores = array();
$i = 0;
while (!$rs->EOF) {
    $supervisores[$i]['cress']         = $rs->fields['cress'];
    $supervisores[$i]['nome']          = $rs->fields['nome'];
    $supervisores[$i]['email']         = $rs->fields['email'];
    $supervisores[$i]['supervisor_id'] = $rs->fields['supervisor_id'];
    $i++;
    $rs->MoveNext();
}

// Nome da instituição.
$instituicao = (string)$db->GetOne(
    "SELECT instituicao FROM instituicoes WHERE id = ?",
    array($instituicao_id)
);

$smarty = new Smarty_estagio;
$smarty->assign("instituicao_id", $instituicao_id);
$smarty->assign("instituicao", $instituicao);
$smarty->assign("supervisores", $supervisores);
$smarty->display("supervisores_x_instituicao.tpl");

exit;

?>