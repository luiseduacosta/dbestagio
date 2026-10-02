<?php

include_once("../../autentica.inc");
require_once("../../libphp/models.php");

// Fluxo secundário de inserção (form_inserir.php).
// O cadastro principal (XAJAX) fica em cadastro.php.
$dados = array();
$dados['nome']          = isset($_POST['nome'])      ? trim($_POST['nome']) : '';
$dados['cpf']           = isset($_POST['cpf'])       ? trim($_POST['cpf']) : null;
$dados['codigo_telefone'] = isset($_POST['codigo_telefone']) ? (int)$_POST['codigo_telefone'] : null;
$dados['telefone']      = isset($_POST['telefone'])  ? trim($_POST['telefone']) : null;
$dados['codigo_celular']= isset($_POST['codigo_celular']) ? (int)$_POST['codigo_celular'] : null;
$dados['celular']       = isset($_POST['celular'])   ? trim($_POST['celular']) : null;
$dados['email']         = isset($_POST['email'])     ? trim($_POST['email']) : null;
$dados['escola']        = isset($_POST['escola'])    ? trim($_POST['escola']) : null;
$dados['ano_formacao']  = isset($_POST['ano_formacao']) ? trim($_POST['ano_formacao']) : null;
$dados['cress']         = isset($_POST['cress'])     ? trim($_POST['cress']) : null;
$dados['regiao']        = isset($_POST['regiao'])    ? (int)$_POST['regiao'] : 0;
$dados['cargo']         = isset($_POST['cargo'])     ? trim($_POST['cargo']) : null;
$dados['user_id']       = (int)(isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : 0);

if ($dados['nome'] === '') {
    die("O campo Nome é obrigatório.");
}
if (mb_strlen($dados['nome'], 'UTF-8') > 70) {
    die("O nome do supervisor excede 70 caracteres.");
}

$novo = new Supervisor();
$novo->preencher($dados);
if (!$novo->save()) {
    error_log("Erro ao inserir supervisor: " . ADODB_Model::$db->ErrorMsg());
    die("Não foi possível inserir o registro na tabela supervisores. Tente novamente.");
}

$novo_id = $novo->getKey();

// Vínculo com a instituição, se informado.
$instituicao_id = (int)(isset($_POST['instituicao_id']) ? $_POST['instituicao_id'] : 0);
if ($instituicao_id > 0) {
    ADODB_Model::$db->Execute(
        "INSERT INTO inst_super (instituicao_id, supervisor_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE supervisor_id = supervisor_id",
        array($instituicao_id, $novo_id)
    );
}

header("Location: ../exibir/ver_cada.php?supervisor_id=$novo_id");
exit;

?>