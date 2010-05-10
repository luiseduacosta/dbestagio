<?

// Configuro a biblioteca pdf
// define("FPDF_FONTPATH","/var/www/fpdf151/font/");
// define("FPDF","/var/www/fpdf151/");

// Intranet
define("FPDF_FONTPATH","/usr/local/htdocs/html/fpdf151/font/");
define("FPDF","/usr/local/htdocs/html/fpdf151/");
require(FPDF."fpdf.php");

// Configuro o banco de dados
// define("ADODB","/var/www/adodb/");
// define("ADODB","/home2/locuss/adodb/");
define("ADODB","/usr/local/htdocs/html/adodb/");
require_once(ADODB.'adodb.inc.php');

/*
$tipo       = "mysql";
$host       = "localhost";
$usuario    = "locuss_ess";
$senha      = "ess123";
$bancodados = "locuss_ess";
*/

$tipo       = "mysql";
$host       = "200.20.112.2";
$usuario    = "ess";
$senha      = "ess123";
$bancodados = "ess";

/*
$tipo       = "mysql";
$host       = "localhost";
$usuario    = "ess";
$senha      = "ess123";
$bancodados = "testecurso";
*/
/*
$tipo       = "pgsql";
$host       = "localhost";
$usuario    = "ess";
$senha      = "ess123";
$bancodados = "ess";
*/

$db = NewADOConnection($tipo);
$db->Connect($host,$usuario,$senha,$bancodados);
$db->debug;
$db->SetFetchMode(ADODB_FETCH_ASSOC);

// Configuro o templatae
define("RAIZ","/usr/local/htdocs/html/");
define("SMARTY_DIR","/usr/local/htdocs/html/Smarty/libs/");
require(SMARTY_DIR.'Smarty.class.php');

?>