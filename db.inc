<?php

require_once __DIR__ . '/vendor/autoload.php';

define("ADODB", __DIR__ . "/vendor/adodb/adodb-php/");


/* */ 
$tipo       = "mysqli";
$host       = "localhost";
/* $host       = "200.20.112.2"; */
$usuario    = "root";
$senha = "";
$bancodados = "ess_app";
/* */
/* 
$tipo       = "pgsql";
$host       = "localhost";
$usuario    = "ess";
$senha = "";
$bancodados = "ess";
*/

$db = NewADOConnection($tipo);
$db->Connect($host,$usuario,$senha,$bancodados);
$db->Execute("set names 'utf8");
$db->debug;
$db->SetFetchMode(ADODB_FETCH_ASSOC);

?>
