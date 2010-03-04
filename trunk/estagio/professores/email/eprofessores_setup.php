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

?>
