<?php

require_once __DIR__ . '/vendor/autoload.php';

// Banco de dados
define("ADODB", __DIR__ . "/vendor/adodb/adodb-php/");

$tipo       = "mysqli";
$host       = "localhost";
$usuario    = "root";
$senha 		= "root";
$bancodados = "ess_apps";

$db = NewADOConnection($tipo);
$db->Connect($host, $usuario, $senha, $bancodados);
$db->Execute("set names 'utf8'");
$db->SetFetchMode(ADODB_FETCH_ASSOC);

// carrega Smarty library files
define("RAIZ", __DIR__);
define("SMARTY_DIR", __DIR__ . "/vendor/smarty/smarty/libs/");

class Smarty_estagio extends Smarty {

	function __construct() {

		parent::__construct();

		$this->cache_dir    = RAIZ.'/smarty/cache/';
		$this->config_dir   = RAIZ.'/smarty/configs/';
		$this->template_dir = RAIZ.'/smarty/templates/';
		$this->compile_dir  = RAIZ.'/smarty/templates_c/';

		// Console de debug do Smarty: desligada por padrao (nao abre em toda pagina).
		// Para abrir somente quando precisar: ?SMARTY_DEBUG na URL (esta pagina) ou
		// ?SMARTY_DEBUG=on / ?SMARTY_DEBUG=off (todas as paginas). A tag {debug}
		// dentro de um .tpl tambem abre a console apenas naquele template.
		// O controle por URL so vale em acesso local (localhost), para nao expor
		// a console quando o sistema estiver em uso por terceiros.
		$this->debugging = false;
		$debug_local = in_array(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '', array('127.0.0.1', '::1'), true);
		$this->debugging_ctrl = $debug_local ? 'URL' : 'NONE';
		$this->caching = true;
		$this->compile_check = true; // Em producao tem que ser false
		$this->clearAllCache();
		$this->assign('app_name','estagio');
	}
}

// define("ESTAGIO","/estagio/");

/* Para produzir documentos PDF */
define("FPDF_FONTPATH", __DIR__ . "/lib/fpdf/font/");
define("FPDF", __DIR__ . "/lib/fpdf/");

/**
 * Converte uma data vinda do banco para o formato pedido, sem emitir aviso
 * quando a coluna vier NULL/vazia ou trouxer o sentinel legado '0000-00-00'.
 *
 * Devolve '' quando nao ha data utilizavel, para que o chamador decida o que
 * fazer. Antes, strtotime(null) retornava false e date() o convertia em epoch,
 * produzindo silenciosamente '01/01/1970' (e um deprecated no PHP 8.1+).
 */
if (!function_exists('estagio_formata_data')) {

function estagio_formata_data($data, $formato) {
	$data = trim((string)$data);
	if ($data === '' || $data === '0000-00-00') {
		return '';
	}
	$ts = strtotime($data);
	return $ts ? date($formato, $ts) : '';
}

}

/* Configurações */
$sql = "select mural_periodo_atual," .
		" curso_turma_atual, " .
		" curso_encerramento_inscricoes, " .
		" termo_compromisso_periodo, " .
		" termo_compromisso_inicio, " .
		" termo_compromisso_final " .
		" from configuracoes";
// echo $sql . "<br>";
$res = $db->Execute($sql);
if ($res === false) die("Nao foi possivel consultar a tabela configuracoes");

$mural_periodo_atual = $res->fields['mural_periodo_atual'];
$curso_turma_atual = $res->fields['curso_turma_atual'];
$curso_encerramento_inscricoes = $res->fields['curso_encerramento_inscricoes'];
$termo_compromisso_periodo = $res->fields['termo_compromisso_periodo'];
$termo_compromisso_inicio = $res->fields['termo_compromisso_inicio'];
$termo_compromisso_final = $res->fields['termo_compromisso_final'];

// Para o mural
define("PERIODO_ATUAL", $mural_periodo_atual);
$periodo_atual = PERIODO_ATUAL;

$_periodo_atual = explode("-",$periodo_atual);
if ($_periodo_atual[1] == 2) $periodo_anterior = $_periodo_atual[0] . "-1";
if ($_periodo_atual[1] == 1) $periodo_anterior = $_periodo_atual[0] - 1 . "-2";

define("PERIODO_ANTERIOR",$periodo_anterior);

// Para o curso - 2009 = turma 8
define("TURMA",$curso_turma_atual);
// Formato USA mes/dia/ano
// define("ENCERRAMENTO",date('m/d/Y',mktime(0,0,0,03,16,2009)));
// Fica '' quando curso_encerramento_inscricoes nao esta configurado (coluna
// anulavel); quem compara o prazo trata o vazio explicitamente.
define("ENCERRAMENTO", estagio_formata_data($curso_encerramento_inscricoes, 'm/d/Y'));
// echo $encerramento = ENCERRAMENTO;
$debug = 0;

// Mailer
define("MAILER", __DIR__ . "/lib/phpmailer/");

// tmp
define("TMP", __DIR__ . "/tmp/");

/* Para o termo de compromisso */
define("TC_PERIODO_ATUAL", $termo_compromisso_periodo);
$tc_periodo_atual = TC_PERIODO_ATUAL;
$_tc_periodo_atual = explode("-",$tc_periodo_atual);
if ($_tc_periodo_atual[1] == 2) $tc_periodo_anterior = $_tc_periodo_atual[0] . "-1";
if ($_tc_periodo_atual[1] == 1) $tc_periodo_anterior = $_tc_periodo_atual[0] - 1 . "-2";
define("TC_PERIODO_ANTERIOR",$tc_periodo_anterior);

// Datas do termo de compromisso (impressas no documento). Vazio em vez de
// 01/01/1970 se a coluna vier sem valor utilizavel.
$validade1 = estagio_formata_data($termo_compromisso_inicio, 'd/m/Y');
$validade2 = estagio_formata_data($termo_compromisso_final, 'd/m/Y');

// Servidor onde esta sendo executado o programa.
// Em CLI (scripts de manutencao, php -r) nao existe SERVER_NAME, entao usa
// localhost como alternativa: evita o aviso sem alterar o acesso via Apache.
$servidor = $_SERVER['SERVER_NAME'] ?? 'localhost';

?>
