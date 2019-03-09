<?php

define("ADODB","/home/luis/html/adodb5/");
require_once(ADODB.'adodb.inc.php');

/* */ 
$tipo       = "mysql";
$host       = "localhost";
/* $host       = "200.20.112.2"; */
$usuario    = "root";
$senha      = "root";
$bancodados = "ess_app";
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
