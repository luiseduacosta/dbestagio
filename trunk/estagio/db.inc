<?php

define("ADODB","/usr/local/htdocs/html/adodb/");
require_once(ADODB.'adodb.inc.php');

/* */ 
$tipo       = "mysql";
$host       = "localhost";
/* $host       = "200.20.112.2"; */
$usuario    = "ess";
$senha      = "ess123";
$bancodados = "ess";
/* */
/* 
$tipo       = "pgsql";
$host       = "localhost";
$usuario    = "ess";
$senha      = "";
$bancodados = "ess";
*/

$db = NewADOConnection($tipo);
$db->Connect($host,$usuario,$senha,$bancodados);
$db->Execute("set names 'utf8");
$db->debug;
$db->SetFetchMode(ADODB_FETCH_ASSOC);

?>
