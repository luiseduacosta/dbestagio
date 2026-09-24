<?php

echo "<h3>Usuários do sistema (tabela: users)</h3>";

include_once("../database.inc");
$opts['tb'] = 'users';

$opts['key'] = 'id';
$opts['key_type'] = 'int';
$opts['sort_field'] = array('id');
$opts['inc'] = 15;
$opts['options'] = 'ACPVDF';
$opts['multiple'] = '4';
$opts['navigation'] = 'DB';
$opts['display'] = array(
	'form'  => true,
	'query' => true,
	'sort'  => true,
	'time'  => true,
	'tabs'  => true
);
$opts['js']['prefix']               = 'PME_js_';
$opts['dhtml']['prefix']            = 'PME_dhtml_';
$opts['cgi']['prefix']['operation'] = 'PME_op_';
$opts['cgi']['prefix']['sys']       = 'PME_sys_';
$opts['cgi']['prefix']['data']      = 'PME_data_';
$opts['language'] = $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '-UTF8';

$trigger_file = __DIR__ . '/trigger_user_password.php';
$opts['triggers']['insert']['before'] = $trigger_file;
$opts['triggers']['update']['before'] = $trigger_file;

$opts['fdd']['id'] = array(
  'name'     => 'ID',
  'select'   => 'T',
  'options'  => 'AVCPDR',
  'maxlen'   => 11,
  'default'  => '0',
  'sort'     => true
);
$opts['fdd']['email'] = array(
  'name'     => 'E-mail',
  'select'   => 'T',
  'maxlen'   => 150,
  'sort'     => true
);
$opts['fdd']['password'] = array(
  'name'     => 'Senha',
  'select'   => 'T',
  'maxlen'   => 255,
  'default'  => '',
  'input'    => 'P',
  'help'     => 'Para novos usuários: digite a senha em texto claro (será criptografada automaticamente). Para editar: deixe em branco para MANTER a senha atual, ou digite uma nova senha para alterar.'
);

require_once '../libphp/phpMyEdit.class.php';
new phpMyEdit($opts);

?>
