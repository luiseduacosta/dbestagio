<?php

// Banco de dados
define("ADODB", dirname(__DIR__, 2) . "/lib/adodb5/");
require(ADODB.'adodb.inc.php');

$tipo       = "mysqli";
$host       = "localhost";
$usuario    = "root";
$senha      = "root";
$bancodados = "ess_apps";

$db = NewADOConnection($tipo);
$db->Connect($host,$usuario,$senha,$bancodados);
$db->debug;
$db->SetFetchMode(ADODB_FETCH_ASSOC);

?>
