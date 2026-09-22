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

		$this->debugging = true;
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
define("ENCERRAMENTO",date('m/d/Y',strtotime($curso_encerramento_inscricoes)));
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

$validade1 = date('d/m/Y',strtotime($termo_compromisso_inicio));
$validade2 = date('d/m/Y',strtotime($termo_compromisso_final));

// Servidor onde esta sendo executado o programa
$servidor = $_SERVER['SERVER_NAME'];

?>
