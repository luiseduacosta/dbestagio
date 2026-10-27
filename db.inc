<?php

require_once __DIR__ . '/vendor/autoload.php';

define("ADODB", __DIR__ . "/vendor/adodb/adodb-php/");

$tipo       = getenv('DB_TYPE') ?: "mysqli";
$host       = getenv('DB_HOST') ?: "localhost";
$usuario    = getenv('DB_USER') ?: "root";
$senha      = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "root";
$bancodados = getenv('DB_NAME') ?: "ess_apps";

$db = NewADOConnection($tipo);
$db->Connect($host, $usuario, $senha, $bancodados);
$db->Execute("set names 'utf8'");
$db->SetFetchMode(ADODB_FETCH_ASSOC);

?>
