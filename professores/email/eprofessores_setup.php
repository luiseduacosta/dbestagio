<?php

// Banco de dados
define("ADODB", dirname(__DIR__, 2) . "/lib/adodb5/");
require(ADODB.'adodb.inc.php');

/* */
$tipo       = "mysqli";
$host       = "200.20.112.3";
$usuario    = "ess";
$senha = "";
$bancodados = "ess";
/* */

$db = NewADOConnection($tipo);
$db->Connect($host,$usuario,$senha,$bancodados);
$db->debug;
$db->SetFetchMode(ADODB_FETCH_ASSOC);

?>
