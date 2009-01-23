<?php

// Banco de dados
define("ADODB","/usr/local/htdocs/html/adodb/");
require(ADODB.'adodb.inc.php');

/* */
$tipo       = "mysql";
$host       = "localhost";
$usuario    = "ess";
$senha      = "ess123";
$bancodados = "ess";
/* */

$db = NewADOConnection($tipo);
$db->Connect($host,$usuario,$senha,$bancodados);
$db->debug;
$db->SetFetchMode(ADODB_FETCH_ASSOC);

// carrega Smarty library files
define("RAIZ","/usr/local/htdocs/html/");
define("SMARTY_DIR","/usr/local/htdocs/html/Smarty/libs/");

require(SMARTY_DIR.'Smarty.class.php');

class Smarty_estagio extends Smarty {

    function Smarty_estagio() {
	$this->Smarty();

	$this->cache_dir    = RAIZ.'estagio/smarty/cache/';
	$this->config_dir   = RAIZ.'estagio/smarty/configs/';
	$this->template_dir = RAIZ.'estagio/smarty/templates/';
	$this->compile_dir  = RAIZ.'estagio/smarty/templates_c/';

	$this->caching = true;
	$this->compile_check = true;
	$this->clear_all_cache();
	$this->assign('app_name','estagio');
    }
}

define("ESTAGIO","/estagio/");

/* Para produzir documentos PDF */
define("FPDF_FONTPATH","/usr/local/htdocs/html/fpdf151/font/");
define("FPDF","/usr/local/htdocs/html/fpdf151/");

// Para o mural
define("PERIODO_ATUAL", "2008-2");
$periodo_atual = PERIODO_ATUAL;
$_periodo_atual = explode("-",$periodo_atual);
if ($_periodo_atual[1] == 2) $periodo_anterior = $_periodo_atual[0] . "-1";
if ($_periodo_atual[1] == 1) $periodo_anterior = $_periodo_atual[0] - 1 . "-2";
define("PERIODO_ANTERIOR",$periodo_anterior);

// Para o curso - 2008 = turma 7
define("TURMA",7);
define("ENCERRAMENTO",date('d/m/Y',mktime(0,0,0,03,01,2008)));

$debug = 0;

// Mailer
define("MAILER","/usr/local/htdocs/html/PHPMailer/");

// tmp
define("TMP","/usr/local/htdocs/html/estagio/tmp/");

/* Para o termo de compromisso */
// Datas de validade do convenio
$validade1 = "01/03/2009";
$validade2 = "01/12/2009";
// Servidor onde esta sendo executado o programa
$servidor = $_SERVER[SERVER_NAME];

?>
