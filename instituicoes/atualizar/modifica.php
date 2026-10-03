<?php

require_once("../../autentica.inc");
require_once("../../libphp/models.php");

// Formulário de edição de instituição (envia para atualiza.php).
$instituicao_id = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0;
$inst = Instituicao::find($instituicao_id);

if ($inst === null) {
    die("Instituição não encontrada (id $instituicao_id).");
}

// Dados exibidos no formulário.
$nome_instituicao     = $inst->instituicao;
$endereco_instituicao = $inst->endereco;
$cep_instituicao      = $inst->cep;
$telefone_instituicao = $inst->telefone;
$id_area_instituicao  = $inst->areaId();
$area_instituicao     = $inst->areaNome();
$beneficio_instituicao = $inst->beneficios;
$fim_de_semana        = $inst->fim_de_semana;
$convenio             = $inst->convenio;
$turma                = '';

if ($inst->countEstagiarios() > 0) {
    $turma = ADODB_Model::$db->GetOne(
        "SELECT MAX(periodo) FROM estagiarios WHERE instituicao_id = ?",
        array($instituicao_id)
    );
}

$smarty = new Smarty_estagio;

$smarty->assign("instituicao_id", $instituicao_id);
$smarty->assign("nome_instituicao", $nome_instituicao);
$smarty->assign("endereco_instituicao", $endereco_instituicao);
$smarty->assign("cep_instituicao", $cep_instituicao);
$smarty->assign("telefone_instituicao", $telefone_instituicao);
$smarty->assign("id_area_instituicao", $id_area_instituicao);
$smarty->assign("beneficio_instituicao", $beneficio_instituicao);
$smarty->assign("fim_de_semana", $fim_de_semana);
$smarty->assign("area_instituicao", $area_instituicao);
$smarty->assign("convenio", $convenio);
$smarty->assign("matriz_areas", Instituicao::areasLista());
$smarty->assign("inst_supervisores", $inst->supervisores());
$smarty->assign("supervisores", Instituicao::supervisoresTodos());
$smarty->assign("turma", $turma);

$smarty->display("instituicao_modifica.tpl");

?>