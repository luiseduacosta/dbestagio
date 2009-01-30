<?php
/*
 * Created on 29/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 

// Banco de dados
define("ADODB","/usr/local/htdocs/html/adodb/");
require(ADODB.'adodb.inc.php');

/* */
$tipo       = "mysql";
$host       = "localhost";
$usuario    = "ess";
$senha      = "ess123";
$bancodados = "pommo";
/* */

$db_pommo = NewADOConnection($tipo);
$db_pommo->Connect($host,$usuario,$senha,$bancodados);
$db_pommo->debug;
$db_pommo->SetFetchMode(ADODB_FETCH_ASSOC);
 
?>
