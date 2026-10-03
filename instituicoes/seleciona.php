<?php

include_once("../autentica.inc");
require_once("../libphp/models.php");

if ($sistema_autentica == 0) {
    echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=http://$url/estagio/login.php'>";
    exit;
}

$opcao = isset($_GET['opcao']) ? $_GET['opcao'] : '';

// Lista (id + nome) para o seletor. Nomes truncados em 50 caracteres,
// reproduzindo o truncate:50 que era aplicado no template.
$instituicao_id   = array();
$nome_instituicao = array();
foreach (Instituicao::listar_todas() as $linha) {
    $instituicao_id[]   = $linha['id'];
    $nome_instituicao[] = mb_substr($linha['instituicao'], 0, 50);
}

$smarty = new Smarty_estagio;
$smarty->assign("opcao", $opcao);
$smarty->assign("instituicao_id", $instituicao_id);
$smarty->assign("nome_instituicao", $nome_instituicao);
$smarty->display("instituicao_seleciona.tpl");

exit;

?>
