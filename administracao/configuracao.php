<?php

include_once(__DIR__ . "/../autentica.inc");
require_once(__DIR__ . "/../libphp/models.php");

$cfg  = Configuracao::obter();
$cols = Configuracao::campos();

// Campos de data recebem o formato dd-mm-aaaa para exibição clara.
$datas = array(
    'curso_abertura_inscricoes',
    'curso_encerramento_inscricoes',
    'termo_compromisso_inicio',
    'termo_compromisso_final',
);

$valores = array();
foreach ($cols as $col => $rotulo) {
    $valor = $cfg->$col;
    if (in_array($col, $datas, true) && $valor !== '' && $valor !== null) {
        $ts = strtotime($valor);
        $valor = ($ts === false) ? $valor : date('d-m-Y', $ts);
    }
    $valores[$col] = array('rotulo' => $rotulo, 'valor' => $valor);
}

$smarty = new Smarty_estagio;
$smarty->assign("valores", $valores);
$smarty->display("configuracao_ver.tpl");

exit;

?>