<?php
/*
 * Created on 29/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
// Banco de dados
define("ADODB", __DIR__ . "/lib/adodb5/");
require(ADODB.'adodb.inc.php');

/* */
$tipo       = "mysqli";
$host       = "localhost";
$usuario    = "root";
$senha      = "root";
$bancodados = "ess_apps";
/* */

$db_pommo = NewADOConnection($tipo);
$db_pommo->Connect($host,$usuario,$senha,$bancodados);
$db_pommo->debug;
$db_pommo->SetFetchMode(ADODB_FETCH_ASSOC);

?>
