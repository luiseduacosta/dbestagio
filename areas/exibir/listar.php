<?php

include_once("../../setup.php");
require_once("../../libphp/models.php");

$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : 'areas.area';

// Whitelist segura das colunas de ordenação (usadas pelos links da template).
$ordens_validas = array(
    'areas.area'               => 'areas.area',
    'professores.nome'         => 'professores.nome',
    'min(estagiarios.periodo)' => 'min(estagiarios.periodo)',
    'max(estagiarios.periodo)' => 'max(estagiarios.periodo)',
);
if (!isset($ordens_validas[$ordem])) {
    $ordem = 'areas.area';
}

$sql = "SELECT areas.id AS area_id, areas.area, professores.nome,
               professores.id AS id_professor,
               MIN(estagiarios.periodo) AS min_periodo,
               MAX(estagiarios.periodo) AS max_periodo
        FROM estagiarios
        JOIN instituicoes ON estagiarios.instituicao_id = instituicoes.id
        LEFT JOIN areas ON instituicoes.area = areas.id
        JOIN professores ON estagiarios.professor_id = professores.id
        GROUP BY instituicoes.area, estagiarios.professor_id, areas.area, professores.nome, professores.id
        ORDER BY " . $ordens_validas[$ordem];

$res_professores = ADODB_Model::$db->Execute($sql);
if ($res_professores === false) {
    error_log("Erro ao consultar areas: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível consultar as tabelas.");
}

$matriz = array();
$i = 0;
while (!$res_professores->EOF) {
    $matriz[$i]['area_id']       = $res_professores->fields['area_id'];
    $matriz[$i]['area']          = $res_professores->fields['area'];
    $matriz[$i]['id_professor']  = $res_professores->fields['id_professor'];
    $matriz[$i]['nome']          = $res_professores->fields['nome'];
    $matriz[$i]['min_periodo']   = $res_professores->fields['min_periodo'];
    $matriz[$i]['max_periodo']   = $res_professores->fields['max_periodo'];
    $i++;
    $res_professores->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("areas", $matriz);
$smarty->display("areas_listar.tpl");

exit;

?>