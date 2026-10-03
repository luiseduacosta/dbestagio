<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

// Sem POST: redireciona para o formulário de inserção.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: formulario.php");
    exit;
}

$instituicao  = isset($_POST['instituicao']) ? trim($_POST['instituicao']) : '';
$natureza     = isset($_POST['natureza']) ? trim($_POST['natureza']) : null;
$area         = isset($_POST['area_id']) && $_POST['area_id'] !== ''
    ? (int)$_POST['area_id']
    : (isset($_POST['area']) && $_POST['area'] !== '' ? (int)$_POST['area'] : null);
$cnpj         = isset($_POST['cnpj']) ? trim($_POST['cnpj']) : null;
$email        = isset($_POST['email']) ? trim($_POST['email']) : null;
$url          = isset($_POST['url']) ? trim($_POST['url']) : null;
$endereco     = isset($_POST['endereco']) ? trim($_POST['endereco']) : null;
$bairro       = isset($_POST['bairro']) ? trim($_POST['bairro']) : null;
$municipio    = isset($_POST['municipio']) ? trim($_POST['municipio']) : null;
$cep          = isset($_POST['cep']) ? trim($_POST['cep']) : null;
$telefone     = isset($_POST['telefone']) ? trim($_POST['telefone']) : null;
$beneficios   = isset($_POST['beneficios']) ? trim($_POST['beneficios']) : null;
$fim_de_semana = isset($_POST['fim_de_semana']) ? $_POST['fim_de_semana'] : 0;
$convenio     = isset($_POST['convenio']) ? trim($_POST['convenio']) : null;
$expira       = isset($_POST['expira']) && $_POST['expira'] !== '' ? $_POST['expira'] : null;
$seguro       = isset($_POST['seguro']) ? $_POST['seguro'] : 0;
$observacoes  = isset($_POST['observacoes']) ? trim($_POST['observacoes']) : null;

if ($instituicao === '') {
    die("<p>O campo Instituição é obrigatório.</p>");
}

$nova = new Instituicao();
$nova->instituicao    = $instituicao;
$nova->natureza       = $natureza;
$nova->area_id        = $area;
$nova->cnpj           = $cnpj;
$nova->email          = $email;
$nova->url            = $url;
$nova->endereco       = $endereco;
$nova->bairro         = $bairro;
$nova->municipio      = $municipio;
$nova->cep            = $cep;
$nova->telefone       = $telefone;
$nova->beneficios     = $beneficios;
$nova->fim_de_semana  = $fim_de_semana;
$nova->convenio       = $convenio;
$nova->expira         = $expira;
$nova->seguro         = $seguro;
$nova->observacoes    = $observacoes;
$nova->user_id        = (int)(isset($usuario_id) ? $usuario_id : 0);
$nova->estagiarios_count = 0;

if (!$nova->save()) {
    error_log("Erro ao inserir instituicao: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível inserir a instituição. Tente novamente.");
}

$novo_id = $nova->getKey();

// Vincula os supervisores existentes selecionados no formulário.
$supervisores = isset($_POST['supervisores']) && is_array($_POST['supervisores']) ? $_POST['supervisores'] : array();
foreach ($supervisores as $supervisor_id) {
    $supervisor_id = (int)$supervisor_id;
    if ($supervisor_id > 0) {
        $nova->vincularSupervisor($supervisor_id);
    }
}

// Opcional: cadastra um supervisor novo e já vincula à instituição.
$novo_supervisor_nome = isset($_POST['novo_supervisor_nome']) ? trim($_POST['novo_supervisor_nome']) : '';
if ($novo_supervisor_nome !== '') {
    $novo_supervisor_cress = isset($_POST['novo_supervisor_cress']) ? trim($_POST['novo_supervisor_cress']) : '';
    $novo_supervisor_email = isset($_POST['novo_supervisor_email']) ? trim($_POST['novo_supervisor_email']) : '';
    $ok_supervisor = Instituicao::$db->Execute(
        "INSERT INTO supervisores (nome, cress, email) VALUES (?, ?, ?)",
        array($novo_supervisor_nome, $novo_supervisor_cress, $novo_supervisor_email)
    );
    if ($ok_supervisor !== false) {
        $nova->vincularSupervisor((int)Instituicao::$db->Insert_ID());
    } else {
        error_log("Erro ao inserir supervisor: " . Instituicao::$db->ErrorMsg());
    }
}

header("Location: ../exibir/ver_cada.php?instituicao_id=$novo_id");
exit;

?>