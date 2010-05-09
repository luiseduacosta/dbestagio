<?php

// Banco de dados
define("ADODB","/usr/local/htdocs/html/adodb/");
require(ADODB.'adodb.inc.php');

/* */
$tipo       = "mysql";
$host       = "200.20.112.2";
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
define("FPDF_FONTPATH","/usr/local/htdocs/html/fpdf153/font/");
define("FPDF","/usr/local/htdocs/html/fpdf153/");

$sql = "select mural_periodo_atual," .
		" curso_turma_atual, " .
		" curso_encerramento_inscricoes, " .
		" termo_compromisso_periodo, " .
		" termo_compromisso_inicio, " .
		" termo_compromisso_final " .
		" from configuracao";
// echo $sql . "<br>";
$res = $db->Execute($sql);
if ($res === false) die("Nao foi possivel consultar a tabela configuracao");

$mural_periodo_atual = $res->fields['mural_periodo_atual'];
// echo $mural_periodo_atual . "<br>";
$curso_turma_atual = $res->fields['curso_turma_atual'];
$curso_encerramento_inscricoes = $res->fields['curso_encerramento_inscricoes'];
$termo_compromisso_periodo = $res->fields['termo_compromisso_periodo'];
$termo_compromisso_inicio = $res->fields['termo_compromisso_inicio'];
$termo_compromisso_final = $res->fields['termo_compromisso_final'];

// Para o mural
define("PERIODO_ATUAL", $mural_periodo_atual);
$periodo_atual = PERIODO_ATUAL;
$_periodo_atual = explode("-",$periodo_atual);
// echo $_periodo_atual[1] . "<br>";
// echo $_periodo_atual[0] . "<br>";
if ($_periodo_atual[1] == 2) $periodo_anterior = $_periodo_atual[0] . "-1";
if ($_periodo_atual[1] == 1) $periodo_anterior = $_periodo_atual[0] - 1 . "-2";
// echo $periodo_anterior . "<br>";
define("PERIODO_ANTERIOR",$periodo_anterior);

// Para o curso - 2009 = turma 8
define("TURMA",$curso_turma_atual);
// Formato USA mes/dia/ano
// define("ENCERRAMENTO",date('m/d/Y',mktime(0,0,0,03,16,2009)));
define("ENCERRAMENTO",date('m/d/Y',strtotime($curso_encerramento_inscricoes)));
// echo $encerramento = ENCERRAMENTO;
$debug = 0;

// Mailer
define("MAILER","/usr/local/htdocs/html/PHPMailer/");

// tmp
define("TMP","/usr/local/htdocs/html/estagio/tmp/");

/* Para o termo de compromisso */
define("TC_PERIODO_ATUAL", $termo_compromisso_periodo);
$tc_periodo_atual = TC_PERIODO_ATUAL;
$_tc_periodo_atual = explode("-",$tc_periodo_atual);
if ($_tc_periodo_atual[1] == 2) $tc_periodo_anterior = $_tc_periodo_atual[0] . "-1";
if ($_tc_periodo_atual[1] == 1) $tc_periodo_anterior = $_tc_periodo_atual[0] - 1 . "-2";
define("TC_PERIODO_ANTERIOR",$tc_periodo_anterior);

// Datas de validade do convenio
// $validade1 = "01/03/2009";
// $validade2 = "01/12/2009";
$validade1 = date('d/m/Y',strtotime($termo_compromisso_inicio));
$validade2 = date('d/m/Y',strtotime($termo_compromisso_final));

// echo $termo_compromisso_inicio . " " . $termo_compromisso_final . "<br>";
// echo $validade1 . " " . $validade2 . "<br>";

// Servidor onde esta sendo executado o programa
$servidor = $_SERVER[SERVER_NAME];

?>
