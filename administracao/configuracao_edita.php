<?php

include_once(__DIR__ . "/../autentica.inc");
require_once(__DIR__ . "/../libphp/models.php");

$cfg   = Configuracao::obter();
$colunas_editar = array_keys(Configuracao::campos());

$campos_data = array(
    'curso_abertura_inscricoes',
    'curso_encerramento_inscricoes',
    'termo_compromisso_inicio',
    'termo_compromisso_final',
);

// ------------------------- Processa o POST -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $erros = array();

    foreach ($colunas_editar as $col) {
        $valor = isset($_POST[$col]) ? trim($_POST[$col]) : '';

        // Validação de campos obrigatórios e tipos.
        if (in_array($col, $campos_data, true)) {
            if ($valor !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
                $erros[] = Configuracao::campos()[$col] . " deve estar no formato AAAA-MM-DD.";
            }
        } elseif ($col === 'curso_turma_atual') {
            if (!ctype_digit($valor)) {
                $erros[] = Configuracao::campos()[$col] . " deve ser um número.";
            }
        }

        $cfg->$col = $valor;
    }

    if (empty($erros)) {
        if (!$cfg->save()) {
            error_log("Erro ao salvar configuração: " . ADODB_Model::$db->ErrorMsg());
            die("Não foi possível salvar a configuração. Tente novamente.");
        }
        header("Location: configuracao.php");
        exit;
    }

    // Em caso de erro, reexibe o formulário mantendo os valores digitados.
    $smarty = new Smarty_estagio;
    $smarty->assign("v", $cfg->toArray(true));
    $smarty->assign("erros", $erros);
    $smarty->display("configuracao_edita.tpl");
    exit;
}

// ------------------------- Exibe o formulário -------------------------
$dados = $cfg->toArray(true);
$smarty = new Smarty_estagio;
$smarty->assign("v", $dados);
$smarty->assign("erros", array());
$smarty->display("configuracao_edita.tpl");

exit;

?>