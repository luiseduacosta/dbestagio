<?php

include_once(__DIR__ . "/../../autentica.inc");
require_once(__DIR__ . "/../../libphp/models.php");
require_once(__DIR__ . "/../validar.php");

$professor_id = isset($_REQUEST['professor_id']) ? (int)$_REQUEST['professor_id'] : 0;

// ----- Exibição do formulário de edição -----
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $prof = Professor::find($professor_id);
    if ($prof === null) {
        die("Professor não encontrado (id $professor_id).");
    }
    $data = $prof->toArray();

    $statuses = Professor::statuses();
    array_shift($statuses); // remove "Todos"

    $v = array(
        'id'               => (int)$data['id'],
        'nome'             => $data['nome'],
        'cpf'              => $data['cpf'],
        'siape'            => $data['siape'],
        'cress'            => $data['cress'],
        'regiao'           => $data['regiao'],
        'codigo_telefone'  => $data['codigo_telefone'],
        'telefone'         => $data['telefone'],
        'codigo_celular'   => $data['codigo_celular'],
        'celular'          => $data['celular'],
        'email'            => strtolower((string)$data['email']),
        'curriculolattes'  => $data['curriculolattes'],
        'atualizacaolattes'=> $data['atualizacaolattes'],
        'dataingresso'     => $data['dataingresso'],
        'tipocargo'        => $data['tipocargo'],
        'departamento'     => $data['departamento'],
        'dataegresso'      => $data['dataegresso'],
        'motivoegresso'    => $data['motivoegresso'],
        'status'           => $data['status'],
        'observacoes'      => $data['observacoes'],
    );

    $smarty = new Smarty_estagio;
    $smarty->assign("statuses", $statuses);
    $smarty->assign("v", $v);
    $smarty->assign("acao", "atualiza.php");
    $smarty->assign("titulo", "Editar professor");
    $smarty->assign("e_edicao", true);
    $smarty->display("professores_form.tpl");
    exit;
}

// ----- Gravação da edição -----
$dados = professor_ler_post();

$prof = Professor::find($professor_id);
if ($prof === null) {
    die("Professor não encontrado (id $professor_id).");
}

$dup = Professor::duplicado($dados['email'], $dados['cpf'], $dados['siape'], $professor_id);
if ($dup !== null) {
    die("Já existe outro professor cadastrado com este e-mail, CPF ou SIAPE (id $dup).");
}

$prof->preencher($dados);
$prof->modified = date('Y-m-d H:i:s');

if (!$prof->save()) {
    error_log("Erro ao atualizar professor: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível atualizar o registro na tabela professores.");
}

header("Location: ../exibir/ver_cada.php?professor_id=$professor_id");
exit;

?>