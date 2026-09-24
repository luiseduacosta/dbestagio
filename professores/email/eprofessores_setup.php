<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Banco de dados
define("ADODB", dirname(__DIR__, 2) . "/vendor/adodb/adodb-php/");


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
