<?php

require_once("../../autentica.inc");
require_once("../../libphp/models.php");

$instituicao_id = isset($_POST['instituicao_id']) ? (int)$_POST['instituicao_id'] : 0;

// Busca a instituição a editar.
$instituicao_mod = Instituicao::find($instituicao_id);
if ($instituicao_mod === null) {
    die("Instituição não encontrada (id $instituicao_id).");
}

// Coleta os campos do formulário.
$dados = array();
$dados['area']          = isset($_POST['area_instituicao']) ? trim($_POST['area_instituicao']) : null;
$dados['instituicao']   = isset($_POST['nome_instituicao']) ? trim($_POST['nome_instituicao']) : '';
$dados['endereco']      = isset($_POST['endereco_instituicao']) ? trim($_POST['endereco_instituicao']) : null;
$dados['cep']           = isset($_POST['cep_instituicao']) ? trim($_POST['cep_instituicao']) : null;
$dados['telefone']      = isset($_POST['telefone_instituicao']) ? trim($_POST['telefone_instituicao']) : null;
$dados['beneficios']    = isset($_POST['beneficio_instituicao']) ? trim($_POST['beneficio_instituicao']) : null;
$dados['fim_de_semana'] = isset($_POST['fim_de_semana']) ? $_POST['fim_de_semana'] : null;
$dados['convenio']      = isset($_POST['convenio']) ? trim($_POST['convenio']) : null;
$dados['seguro']        = isset($_POST['seguro']) ? $_POST['seguro'] : null;

// Validações de tamanho (mesmas regras antigas, agora com mensagens claras).
$limites = array(
    'instituicao' => 75,
    'endereco'    => 104,
    'cep'         => 9,
);
foreach ($limites as $campo => $max) {
    if (isset($dados[$campo]) && mb_strlen((string)$dados[$campo], 'UTF-8') > $max) {
        echo "Campo '" . htmlspecialchars($campo) . "' excede $max caracteres (tamanho atual: "
           . mb_strlen((string)$dados[$campo], 'UTF-8') . ").<br>";
        exit;
    }
}

// Nome da instituição é obrigatório.
if ($dados['instituicao'] === '') {
    die("<p>O nome da instituição é obrigatório.</p>");
}

// Aplica os dados no modelo e salva (UPDATE parametrizado via ADODB_Model).
foreach ($dados as $campo => $valor) {
    $instituicao_mod->$campo = $valor;
}

if (!$instituicao_mod->save()) {
    error_log("Erro ao atualizar instituicao: " . $db->ErrorMsg());
    die("Não foi possível atualizar a tabela instituicoes. Tente novamente.");
}

header("Location: ../exibir/ver_cada.php?instituicao_id=$instituicao_id");
exit;

?>