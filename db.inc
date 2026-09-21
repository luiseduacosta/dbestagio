<?php

require_once __DIR__ . '/vendor/autoload.php';

define("ADODB", __DIR__ . "/vendor/adodb/adodb-php/");

$tipo       = "mysqli";
$host       = "localhost";
$usuario    = "root";
$senha      = "root";
$bancodados = "ess_apps";

$db = NewADOConnection($tipo);
$db->Connect($host, $usuario, $senha, $bancodados);
$db->Execute("set names 'utf8");
$db->SetFetchMode(ADODB_FETCH_ASSOC);

?>
