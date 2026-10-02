<?php
/**
 * models.php — Carrega a camada de modelos ADOdb e liga a conexão $db existente.
 *
 * Inclua DEPOIS de qualquer script que defina $db (setup.php ou db.inc):
 *
 *   require_once "setup.php";
 *   require_once "libphp/models.php";
 *
 * Também registra um autoload simples para classes das tabelas comuns do
 * sistema (alunos, áreas, instituições, supervisores, etc.),
 * que podem herdar de ADODB_Model.
 */

if (!function_exists('adodb_models_autoload')) {

function adodb_models_autoload($class) {
    // Mapeia classe -> arquivo em libphp/ quando existir.
    $file = __DIR__ . '/' . $class . '.php';
    if (is_file($file)) {
        require_once $file;
        return;
    }

    // Carrega models dentro de libphp/models/<Classe>.php se existirem.
    $file2 = __DIR__ . '/models/' . $class . '.php';
    if (is_file($file2)) {
        require_once $file2;
    }
}

spl_autoload_register('adodb_models_autoload');

}

// Liga a conexão global $db na base dos modelos.
require_once __DIR__ . '/Model.php';
if (isset($GLOBALS['db'])) {
    ADODB_Model::setDb($GLOBALS['db']);
}

// Garante que os modelos comuns estejam carregados.
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Configuracao.php';
require_once __DIR__ . '/Instituicao.php';
require_once __DIR__ . '/Supervisor.php';
require_once __DIR__ . '/Area.php';
require_once __DIR__ . '/Visita.php';
require_once __DIR__ . '/Mural.php';
require_once __DIR__ . '/Inscricao.php';
require_once __DIR__ . '/Aluno.php';
require_once __DIR__ . '/Professor.php';
require_once __DIR__ . '/Estagiario.php';

?>