<?php
// include_once("superior.html");
?>

<h3>Alunos da disciplina ética do curso de Serviço Social</h3>

<?php

// MySQL host name, user name, password, database, and table
include_once("../../database.inc");
echo $opts['tb'] = 'alunos_etica';

// Name of field which is the unique key
$opts['key'] = 'id';

// Type of key field (int/real/string/date etc.)
$opts['key_type'] = 'int';

// Sorting field(s)
$opts['sort_field'] = array('nome');

// Number of records to display on the screen
// Value of -1 lists all records in a table
$opts['inc'] = 10;

// Options you wish to give the users
// A - add,  C - change, P - copy, V - view, D - delete,
// F - filter, I - initial sort suppressed
$opts['options'] = 'ACVDF';

// Number of lines to display on multiple selection filters
$opts['multiple'] = '4';

// Navigation style: B - buttons (default), T - text links, G - graphic links
// Buttons position: U - up, D - down (default)
$opts['navigation'] = 'DB';

// Display special page elements
$opts['display'] = array(
	'form'  => true,
	'query' => true,
	'sort'  => true,
	'time'  => true,
	'tabs'  => true
);

// Set default prefixes for variables
$opts['js']['prefix']               = 'PME_js_';
$opts['dhtml']['prefix']            = 'PME_dhtml_';
$opts['cgi']['prefix']['operation'] = 'PME_op_';
$opts['cgi']['prefix']['sys']       = 'PME_sys_';
$opts['cgi']['prefix']['data']      = 'PME_data_';

/* Get the user's default language and use it if possible or you can
   specify particular one you want to use. Refer to official documentation
   for list of available languages. */
$opts['language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '-UTF8';

/* Table-level filter capability. If set, it is included in the WHERE clause
   of any generated SELECT statement in SQL query. This gives you ability to
   work only with subset of data from table.

$opts['filters'] = "column1 like '%11%' AND column2<17";
$opts['filters'] = "section_id = 9";
$opts['filters'] = "PMEtable0.sessions_count > 200";
*/

/* Field definitions
*/

$opts['fdd']['id'] = array(
  'name'     => 'ID',
  'select'   => 'T',
  'options'  => 'AVCPDR', // auto increment
  'maxlen'   => 4,
  'default'  => '0',
  'sort'     => true
);
$opts['fdd']['ordem'] = array(
  'name'     => 'Ordem',
  'select'   => 'T',
  'maxlen'   => 2,
  'sort'     => true
);
$opts['fdd']['nome'] = array(
  'name'     => 'Nome',
  'select'   => 'T',
  'size'     => '50',
  'maxlen'   => '50',
  'sort'     => true
);
$opts['fdd']['registro'] = array(
  'name'     => 'Registro',
  'select'   => 'T',
  'maxlen'   => 9,
  'default'  => '0',
  'sort'     => true
);
$opts['fdd']['periodo'] = array(
  'name'     => 'Período',
  'select'   => 'T',
  'maxlen'   => 6,
  'sort'     => true
);
$opts['fdd']['turno'] = array(
  'name'     => 'Turno',
  'select'   => 'T',
  'maxlen'   => 1,
  'sort'     => true
);

// Now important call to phpMyEdit
require_once('../../libphp/phpMyEdit.class.php');
new phpMyEdit($opts);

?>
